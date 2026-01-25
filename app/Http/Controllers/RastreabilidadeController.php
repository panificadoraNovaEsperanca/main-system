<?php

namespace App\Http\Controllers;

use App\Http\Requests\RastreabilidadeConfiguracaoRequest;
use App\Http\Requests\RastreabilidadeRequest;
use App\Http\Requests\RastreabilidadeRelatorioRequest;
use App\Models\Insumo;
use App\Models\Produto;
use App\Models\ProdutoRastreavel;
use App\Models\ProdutoRastreavelInsumo;
use App\Models\RastreabilidadeIngrediente;
use App\Models\RastreabilidadeLote;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RastreabilidadeController extends Controller
{
    /**
     * Exibe tela de configuração de produtos rastreáveis
     */
    public function configuracao(): View
    {
        // Buscar produtos já configurados como rastreáveis primeiro
        $produtosRastreaveis = ProdutoRastreavel::with(['insumos' => function($query) {
            $query->orderBy('ordem');
        }])->get()->keyBy('produto_id');
        
        $query = Produto::whereNull('deleted_at');
        
        // Filtro de busca
        if (request()->has('search') && !empty(request()->search)) {
            $query->whereRaw('LOWER(nome) LIKE ?', ['%' . strtolower(request()->search) . '%']);
        }
        
        // Ordenar: primeiro os rastreáveis ativos, depois os rastreáveis inativos, depois os não rastreáveis
        // Dentro de cada grupo, ordenar por nome
        $produtos = $query->orderByRaw('CASE 
            WHEN EXISTS (SELECT 1 FROM produtos_rastreaveis WHERE produtos_rastreaveis.produto_id = produtos.id AND produtos_rastreaveis.ativo = true) THEN 0
            WHEN EXISTS (SELECT 1 FROM produtos_rastreaveis WHERE produtos_rastreaveis.produto_id = produtos.id AND produtos_rastreaveis.ativo = false) THEN 1
            ELSE 2
        END')
        ->orderBy('nome')
        ->paginate(request()->paginacao ?? 20);
        
        $insumos = Insumo::whereNull('deleted_at')->orderBy('nome')->get();
        
        return view('rastreabilidade.configuracao', compact('produtos', 'insumos', 'produtosRastreaveis'));
    }

    /**
     * Salva configuração de produtos rastreáveis (ativar/desativar)
     * As receitas são salvas individualmente via AJAX
     */
    public function storeConfiguracao(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // Desativar todos os produtos rastreáveis primeiro
            ProdutoRastreavel::where('ativo', true)->update(['ativo' => false]);

            // Processar produtos do request (apenas ativar os marcados)
            if (is_array($request->produtos)) {
                foreach ($request->produtos as $produtoId => $produtoData) {
                    // Verificar se produto está marcado como ativo
                    $ativo = isset($produtoData['ativo']) && $produtoData['ativo'];
                    
                    if ($ativo) {
                        // Verificar se o produto tem receita configurada
                        $produtoRastreavel = ProdutoRastreavel::where('produto_id', $produtoId)->first();
                        
                        if ($produtoRastreavel) {
                            // Verificar se tem ingredientes
                            $temIngredientes = ProdutoRastreavelInsumo::where('produto_rastreavel_id', $produtoRastreavel->id)->exists();
                            
                            if (!$temIngredientes) {
                                // Se não tem receita, apenas ativar mas avisar
                                $produtoRastreavel->update(['ativo' => true]);
                            } else {
                                // Ativar produto que já tem receita
                                $produtoRastreavel->update(['ativo' => true]);
                            }
                        } else {
                            // Produto não tem receita configurada ainda, criar mas desativar
                            ProdutoRastreavel::create([
                                'produto_id' => $produtoId,
                                'ativo' => false // Não ativar sem receita
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            $redirectUrl = route('rastreabilidade.configuracao');
            if (request()->has('search') || request()->has('paginacao') || request()->has('page')) {
                $redirectUrl .= '?' . http_build_query(request()->only(['search', 'paginacao', 'page']));
            }
            return redirect($redirectUrl)->with('messages', ['success' => ['Configuração salva com sucesso!']]);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('messages', ['error' => ['Não foi possível salvar a configuração! ' . $e->getMessage()]])->withInput($request->all());
        }
    }

    /**
     * Salva receita de um produto individual via AJAX
     */
    public function storeReceita(Request $request)
    {
        try {
            $request->validate([
                'produto_id' => 'required|exists:produtos,id',
                'insumos' => 'required|array|min:1',
                'insumos.*.insumo_id' => 'required|exists:insumos,id',
                'insumos.*.ordem' => 'nullable|integer|min:0',
            ]);

            DB::beginTransaction();

            // Criar ou atualizar produto rastreável
            $produtoRastreavel = ProdutoRastreavel::updateOrCreate(
                ['produto_id' => $request->produto_id],
                ['ativo' => true]
            );

            // Remover ingredientes antigos
            ProdutoRastreavelInsumo::where('produto_rastreavel_id', $produtoRastreavel->id)->delete();

            // Adicionar novos ingredientes
            foreach ($request->insumos as $index => $insumoData) {
                ProdutoRastreavelInsumo::create([
                    'produto_rastreavel_id' => $produtoRastreavel->id,
                    'insumo_id' => (int)$insumoData['insumo_id'],
                    'ordem' => isset($insumoData['ordem']) ? (int)$insumoData['ordem'] : ($index + 1)
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Receita salva com sucesso!',
                'produto_id' => $produtoRastreavel->produto_id,
                'insumos_count' => count($request->insumos)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível salvar a receita! ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calcula o número do lote no formato MMDD (dia seguinte à data fornecida)
     */
    private function calcularLote(Carbon $data = null): string
    {
        if (!$data) {
            $data = Carbon::today();
        }
        $diaSeguinte = $data->copy()->addDay();
        $mes = str_pad($diaSeguinte->month, 2, '0', STR_PAD_LEFT);
        $dia = str_pad($diaSeguinte->day, 2, '0', STR_PAD_LEFT);
        return $mes . $dia;
    }

    /**
     * Verifica se o dia anterior foi completamente preenchido
     */
    private function verificarDiaAnterior(Carbon $dataBase = null): array
    {
        if (!$dataBase) {
            $dataBase = Carbon::today();
        }
        
        // Data marco: a partir desta data, não é necessário validar dia anterior
        $dataMarco = Carbon::create(2026, 1, 23);
        
        // Se a data base for igual ou anterior à data marco, não precisa validar
        if ($dataBase->lte($dataMarco)) {
            return [
                'completo' => true,
                'produtos_faltando' => [],
                'data_anterior' => $dataBase->copy()->subDay()->format('d/m/Y')
            ];
        }
        
        $diaAnterior = $dataBase->copy()->subDay();
        
        // Se o dia anterior for anterior à data marco, não precisa validar
        if ($diaAnterior->lt($dataMarco)) {
            return [
                'completo' => true,
                'produtos_faltando' => [],
                'data_anterior' => $diaAnterior->format('d/m/Y')
            ];
        }
        
        $produtosRastreaveis = ProdutoRastreavel::where('ativo', true)->pluck('id');
        
        $lotesDiaAnterior = RastreabilidadeLote::whereDate('data_producao', $diaAnterior)
            ->whereIn('produto_rastreavel_id', $produtosRastreaveis)
            ->pluck('produto_rastreavel_id')
            ->unique();

        $produtosFaltando = $produtosRastreaveis->diff($lotesDiaAnterior);
        
        return [
            'completo' => $produtosFaltando->isEmpty(),
            'produtos_faltando' => $produtosFaltando->toArray(),
            'data_anterior' => $diaAnterior->format('d/m/Y')
        ];
    }

    /**
     * Exibe tela de registro diário de rastreabilidade
     */
    public function index(Request $request): View
    {
        // Obter data selecionada ou usar hoje como padrão
        $dataSelecionada = $request->has('data') && $request->data 
            ? Carbon::parse($request->data) 
            : Carbon::today();
        
        // Não permitir datas futuras
        if ($dataSelecionada->isFuture()) {
            $dataSelecionada = Carbon::today();
        }
        
        $lote = $this->calcularLote($dataSelecionada);
        $verificacaoDiaAnterior = $this->verificarDiaAnterior($dataSelecionada);
        
        $produtosRastreaveis = ProdutoRastreavel::where('ativo', true)
            ->with(['produto', 'insumos.insumo'])
            ->get()
            ->sortBy(function($item) {
                return $item->produto->nome ?? '';
            })
            ->values();

        // Buscar registros existentes para a data selecionada
        $lotesDataSelecionada = RastreabilidadeLote::whereDate('data_producao', $dataSelecionada)
            ->with(['ingredientes.insumo', 'user', 'ultimaEdicaoPor'])
            ->get()
            ->keyBy('produto_rastreavel_id');

        return view('rastreabilidade.index', compact(
            'lote',
            'dataSelecionada',
            'verificacaoDiaAnterior',
            'produtosRastreaveis',
            'lotesDataSelecionada'
        ));
    }

    /**
     * Salva registro de rastreabilidade
     */
    public function store(RastreabilidadeRequest $request): RedirectResponse
    {
        try {
            $dataProducao = Carbon::parse($request->data_producao);
            
            // Não permitir datas futuras
            if ($dataProducao->isFuture()) {
                return back()->with('messages', ['error' => ['Não é possível registrar uma data futura.']])->withInput($request->all());
            }
            
            // Verificar se dia anterior foi preenchido
            $verificacaoDiaAnterior = $this->verificarDiaAnterior($dataProducao);
            if (!$verificacaoDiaAnterior['completo']) {
                return back()->with('messages', ['error' => ['Não é possível registrar esta data. É necessário completar o registro do dia anterior (' . $verificacaoDiaAnterior['data_anterior'] . ') primeiro.']])->withInput($request->all());
            }

            DB::beginTransaction();

            // Verificar se já existe registro para este produto, lote e data
            $loteExistente = RastreabilidadeLote::where('produto_rastreavel_id', $request->produto_rastreavel_id)
                ->where('lote', $request->lote)
                ->whereDate('data_producao', $dataProducao)
                ->first();

            if ($loteExistente) {
                // Atualizar registro existente
                $loteExistente->update([
                    'responsavel' => $request->responsavel,
                    'nao_produzido' => $request->nao_produzido ?? false,
                    'ultima_edicao_em' => Carbon::now(),
                    'ultima_edicao_por' => Auth::id()
                ]);

                // Remover ingredientes antigos
                RastreabilidadeIngrediente::where('rastreabilidade_lote_id', $loteExistente->id)->delete();

                // Salvar novos ingredientes se não foi marcado como "não produzido"
                if (!$loteExistente->nao_produzido && !empty($request->ingredientes) && is_array($request->ingredientes)) {
                    foreach ($request->ingredientes as $ingredienteData) {
                        if (isset($ingredienteData['insumo_id']) && !empty($ingredienteData['insumo_id'])) {
                            RastreabilidadeIngrediente::create([
                                'rastreabilidade_lote_id' => $loteExistente->id,
                                'insumo_id' => (int)$ingredienteData['insumo_id'],
                                'marca' => !empty($ingredienteData['marca']) ? $ingredienteData['marca'] : null,
                                'lote_ingrediente' => !empty($ingredienteData['lote_ingrediente']) ? $ingredienteData['lote_ingrediente'] : null,
                                'validade_original' => !empty($ingredienteData['validade_original']) ? Carbon::parse($ingredienteData['validade_original']) : null,
                                'data_abertura' => !empty($ingredienteData['data_abertura']) ? Carbon::parse($ingredienteData['data_abertura']) : null,
                                'validade_apos_aberto' => !empty($ingredienteData['validade_apos_aberto']) ? Carbon::parse($ingredienteData['validade_apos_aberto']) : null,
                                'caracteristica_sensorial' => !empty($ingredienteData['caracteristica_sensorial']) ? $ingredienteData['caracteristica_sensorial'] : 'Conforme'
                            ]);
                        }
                    }
                }

                $lote = $loteExistente;
            } else {
                // Criar novo lote de rastreabilidade
                $lote = RastreabilidadeLote::create([
                    'produto_rastreavel_id' => $request->produto_rastreavel_id,
                    'lote' => $request->lote,
                    'data_producao' => $dataProducao,
                    'responsavel' => $request->responsavel,
                    'nao_produzido' => $request->nao_produzido ?? false,
                    'user_id' => Auth::id()
                ]);

                // Salvar ingredientes se não foi marcado como "não produzido"
                if (!$lote->nao_produzido && !empty($request->ingredientes) && is_array($request->ingredientes)) {
                    foreach ($request->ingredientes as $ingredienteData) {
                        if (isset($ingredienteData['insumo_id']) && !empty($ingredienteData['insumo_id'])) {
                            RastreabilidadeIngrediente::create([
                                'rastreabilidade_lote_id' => $lote->id,
                                'insumo_id' => (int)$ingredienteData['insumo_id'],
                                'marca' => !empty($ingredienteData['marca']) ? $ingredienteData['marca'] : null,
                                'lote_ingrediente' => !empty($ingredienteData['lote_ingrediente']) ? $ingredienteData['lote_ingrediente'] : null,
                                'validade_original' => !empty($ingredienteData['validade_original']) ? Carbon::parse($ingredienteData['validade_original']) : null,
                                'data_abertura' => !empty($ingredienteData['data_abertura']) ? Carbon::parse($ingredienteData['data_abertura']) : null,
                                'validade_apos_aberto' => !empty($ingredienteData['validade_apos_aberto']) ? Carbon::parse($ingredienteData['validade_apos_aberto']) : null,
                                'caracteristica_sensorial' => !empty($ingredienteData['caracteristica_sensorial']) ? $ingredienteData['caracteristica_sensorial'] : 'Conforme'
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            $redirectUrl = route('rastreabilidade.index', ['data' => $dataProducao->format('Y-m-d')]);
            $mensagem = $loteExistente ? 'Registro atualizado com sucesso!' : 'Rastreabilidade registrada com sucesso!';
            return redirect($redirectUrl)->with('messages', ['success' => [$mensagem]]);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('messages', ['error' => ['Não foi possível salvar o registro! ' . $e->getMessage()]])->withInput($request->all());
        }
    }

    /**
     * Exclui um registro de rastreabilidade
     */
    public function destroy(RastreabilidadeLote $rastreabilidadeLote): RedirectResponse
    {
        try {
            DB::beginTransaction();
            
            // Excluir ingredientes relacionados
            RastreabilidadeIngrediente::where('rastreabilidade_lote_id', $rastreabilidadeLote->id)->delete();
            
            // Excluir o lote
            $rastreabilidadeLote->delete();
            
            DB::commit();
            
            $redirectUrl = route('rastreabilidade.index');
            if (request()->has('data')) {
                $redirectUrl .= '?data=' . request()->data;
            }
            
            return redirect($redirectUrl)->with('messages', ['success' => ['Registro excluído com sucesso!']]);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('messages', ['error' => ['Não foi possível excluir o registro! ' . $e->getMessage()]]);
        }
    }

    /**
     * Exibe tela de filtro para relatório
     */
    public function relatorioIndex(): View
    {
        return view('relatorios.rastreabilidade');
    }

    /**
     * Gera relatório PDF de rastreabilidade
     */
    public function gerarRelatorio(RastreabilidadeRelatorioRequest $request)
    {
        try {
            $data = Carbon::parse($request->data);

            $lotes = RastreabilidadeLote::whereDate('data_producao', $data)
                ->with([
                    'produtoRastreavel.produto',
                    'ingredientes.insumo',
                    'user'
                ])
                ->orderBy('produto_rastreavel_id')
                ->get();

            if ($lotes->isEmpty()) {
                return back()->with('messages', ['error' => ['Não há registros de rastreabilidade para a data selecionada.']]);
            }

            // Agrupar por produto
            $lotesPorProduto = $lotes->groupBy('produto_rastreavel_id');

            $pdf = Pdf::loadView('relatorios.pdf.rastreabilidade', [
                'data' => $data,
                'lotesPorProduto' => $lotesPorProduto,
                'dataGeracao' => Carbon::now()
            ])->setPaper('a4', 'portrait')
              ->setOption('margin-top', 20)
              ->setOption('margin-bottom', 20)
              ->setOption('margin-left', 15)
              ->setOption('margin-right', 15);

            $nomeArquivo = 'Relatorio_Rastreabilidade_' . $data->format('d-m-Y') . '.pdf';
            return $pdf->download($nomeArquivo);
        } catch (Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível gerar o relatório! ' . $e->getMessage()]]);
        }
    }
}
