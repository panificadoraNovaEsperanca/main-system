<?php

namespace App\Http\Controllers;

use App\Http\Requests\MotoristaRequest;
use App\Http\Requests\RelatorioMotorista;
use App\Models\Motorista;
use App\Models\MotoristaUser;
use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MotoristaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $motoristas = Motorista::withTrashed()->when(request()->search != '', function ($query) {
            $query->where(DB::raw('LOWER(nome)'), 'LIKE', '%' . strtolower(request()->search) . '%');
        })->paginate(request()->paginacao ?? 10);
        return view('motorista.index', compact('motoristas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('motorista.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MotoristaRequest $request)
    {
        try {
            Motorista::create($request->except(['_token']));
            return redirect(route('motorista.index'))->with('messages', ['success' => ['Motorista criado com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível salvar o motorista. Tente novamente mais tarde!']])->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $motorista = Motorista::findOrFail($id);
            return view('motorista.form', compact('motorista',));
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível encontrar o motorista!']]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            Motorista::findOrFail($id)->update($request->except(['_token']));
            return redirect(route('motorista.index'))->with('messages', ['success' => ['Motorista criado com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível salvar o motorista. Tente novamente mais tarde!']])->withInput($request->all());
        }
    }


    public function destroy(int $id)
    {
        try {
            Motorista::findOrFail($id)->delete();
            return back()->with('messages', ['success' => ['Motorista excluído com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível excluír o motorista!']]);
        }
    }

    public function ativar(int $motorista_id)
    {
        try {
            Motorista::withTrashed()->where('id', $motorista_id)->update(['deleted_at' => null]);
            return back()->with('messages', ['success' => ['Motorista ativado com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível ativar a categoria!' . $e->getMessage()]]);
        }
    }
    public function motoristaByName()
    {
        try {
            $name = request()->query('nome');

            if ($name == '') {
                return response()->json(['success' => true, 'data' => []], 200);
            }
            $resultados = Motorista::where(DB::raw('LOWER(nome)'), 'LIKE', '%' . strtolower($name) . '%')->select(['nome', 'id', 'turno'])->get();
            return response()->json(['success' => true, 'data' => $resultados], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'data' => null, 'message' => 'Erro ao processar requisição. Tente novamente mais tarde.' . $e->getMessage()], 400);
        }
    }
    public function relatorioMotorista(RelatorioMotorista $request)
    {
        try {
            ini_set('memory_limit', '-1');
            $inicio = Carbon::createFromFormat('d/m/Y', $request->data)->startOfDay();
            $fim = Carbon::createFromFormat('d/m/Y', $request->data)->endOfDay();

            $motoristasId = Motorista::when($request->motorista != null && $request->motorista != '', function ($query) use ($request) {
                $query->where('id', $request->motorista);
            })->whereHas('pedidos', function ($query2) use ($inicio, $fim) {
                $query2->whereBetween('dt_previsao', [$inicio, $fim]);
            })->pluck('id');
            $dados = [];

            foreach ($motoristasId as $motorista_id) {
                $pedidos = Pedido::with(['produtos', 'cliente'])->where('motorista_id', $motorista_id)->whereBetween('dt_previsao', [$inicio, $fim])->orderBy('dt_previsao', 'ASC')->get();
                $dados[$motorista_id]['motorista'] = Motorista::find($motorista_id);
                $dados[$motorista_id]['pedidos'] = $pedidos;
            }


            $pdf =  Pdf::loadView('relatorios.pdf.motorista', [
                'pedidos' => $dados,
                'dia' => $inicio->format('d/m/Y')
            ]);
            return $pdf->download("Relatório entregas {$inicio->format('d/m/Y')}.pdf");
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'data' => null, 'message' => 'Erro ao processar requisição. Tente novamente mais tarde.' . $e->getMessage()], 400);
        }
    }
    public function etiquetas(RelatorioMotorista $request)
    {
        try {
            ini_set('memory_limit', '-1');
            $inicio = Carbon::createFromFormat('d/m/Y', $request->data)->startOfDay();
            $fim = Carbon::createFromFormat('d/m/Y', $request->data)->endOfDay();

            // Flag opcional - se true, imprime apenas 1 etiqueta (opcional para teste)
            $modoTeste = $request->get('teste', false);

            $motoristasId = Motorista::when($request->motorista != null && $request->motorista != '', function ($query) use ($request) {
                $query->where('id', $request->motorista);
            })->whereHas('pedidos', function ($query2) use ($inicio, $fim) {
                $query2->whereBetween('dt_previsao', [$inicio, $fim]);
            })->pluck('id');

            $etiquetas = [];
            $contadorEtiquetas = 0;
            // Se modo teste estiver ativo, limita a 1 etiqueta. Caso contrário, imprime todas
            $maxEtiquetas = $modoTeste ? 1 : PHP_INT_MAX;

            foreach ($motoristasId as $motorista_id) {
                $pedidos = Pedido::with(['produtos.produto.setor', 'cliente', 'motorista'])
                    ->where('motorista_id', $motorista_id)
                    ->whereBetween('dt_previsao', [$inicio, $fim])
                    ->orderBy('dt_previsao', 'ASC')
                    ->get();

                foreach ($pedidos as $pedido) {
                    // Ordenar produtos por setor
                    $produtosOrdenados = $pedido->produtos->sortBy(function ($pedidoProduto) {
                        return $pedidoProduto->produto->setor->nome ?? '999';
                    });

                    foreach ($produtosOrdenados as $pedidoProduto) {
                        $produto = $pedidoProduto->produto;
                        $quantidadeEmbalagem = $produto->quantidade_embalagem ?? 1;
                        $quantidadeTotal = $pedidoProduto->quantidade;

                        // Calcular quantas etiquetas são necessárias
                        $etiquetasNecessarias = ceil($quantidadeTotal / $quantidadeEmbalagem);

                        for ($i = 0; $i < $etiquetasNecessarias; $i++) {
                            // Se já atingimos o máximo de etiquetas, para a geração
                            if ($contadorEtiquetas >= $maxEtiquetas) {
                                break 3;
                            }

                            $quantidadeEtiqueta = min($quantidadeEmbalagem, $quantidadeTotal - ($i * $quantidadeEmbalagem));

                            // Pegar apenas o nome do setor (se existir a relação)
                            $setorNome = $produto->setor ? $produto->setor->nome : 'N/A';

                            $etiquetas[] = [
                                'data' => $inicio->format('d/m/Y'),
                                'motorista' => $this->removerCaracteresEspeciais($pedido->motorista->nome),
                                'cliente' => $this->removerCaracteresEspeciais($pedido->cliente->name),
                                'produto' => $this->removerCaracteresEspeciais($produto->nome),
                                'quantidade' => $quantidadeEtiqueta,
                                'pedido' => $pedido->id,
                                'setor' => $this->removerCaracteresEspeciais($setorNome),
                                'indice' => $contadorEtiquetas + 1,
                                'modoTeste' => $modoTeste
                            ];

                            $contadorEtiquetas++;
                        }

                        if ($contadorEtiquetas >= $maxEtiquetas) {
                            break 2;
                        }
                    }

                    if ($contadorEtiquetas >= $maxEtiquetas) {
                        break;
                    }
                }

                if ($contadorEtiquetas >= $maxEtiquetas) {
                    break;
                }
            }

            // Gerar ZPL
            $zpl = $this->gerarZPL($etiquetas);

            $nomeArquivo = $modoTeste
                ? "etiquetas-teste-{$inicio->format('d-m-Y')}.zpl"
                : "etiquetas-{$inicio->format('d-m-Y')}.zpl";

            return response($zpl)
                ->header('Content-Type', 'text/plain')
                ->header('Content-Disposition', "attachment; filename={$nomeArquivo}");
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Erro ao processar requisição: ' . $e->getMessage()
            ], 400);
        }
    }

    private function removerCaracteresEspeciais($string)
    {
        // Remove acentos e caracteres especiais
        $string = preg_replace('/[áàâãä]/u', 'a', $string);
        $string = preg_replace('/[éèêë]/u', 'e', $string);
        $string = preg_replace('/[íìîï]/u', 'i', $string);
        $string = preg_replace('/[óòôõö]/u', 'o', $string);
        $string = preg_replace('/[úùûü]/u', 'u', $string);
        $string = preg_replace('/[ç]/u', 'c', $string);
        $string = preg_replace('/[ÁÀÂÃÄ]/u', 'A', $string);
        $string = preg_replace('/[ÉÈÊË]/u', 'E', $string);
        $string = preg_replace('/[ÍÌÎÏ]/u', 'I', $string);
        $string = preg_replace('/[ÓÒÔÕÖ]/u', 'O', $string);
        $string = preg_replace('/[ÚÙÛÜ]/u', 'U', $string);
        $string = preg_replace('/[Ç]/u', 'C', $string);
        $string = preg_replace('/[ñ]/u', 'n', $string);
        $string = preg_replace('/[Ñ]/u', 'N', $string);

        // Remove todos os outros caracteres especiais, mantendo apenas letras, números e espaços
        $string = preg_replace('/[^a-zA-Z0-9\s]/', '', $string);

        // Remove múltiplos espaços
        $string = preg_replace('/\s+/', ' ', $string);

        return trim($string);
    }

    private function gerarZPL($etiquetas)
    {
        $zpl = "";

        foreach ($etiquetas as $index => $etiqueta) {
            // Configurações para etiqueta 100x50mm (203 DPI - ZD220)
            // 100mm = 3.937" = ~800 dots | 50mm = 1.969" = ~400 dots
            $zpl .= "^XA\n"; // Início da etiqueta
            $zpl .= "^MMT\n"; // Modo de thermal transfer
            $zpl .= "^PR6\n"; // Velocidade de impressão
            $zpl .= "^MD30\n"; // Densidade máxima (0-30, 30 = máximo escuro)
            $zpl .= "^PW800\n"; // Largura: 800 dots = 100mm @ 203 DPI
            $zpl .= "^LL400\n"; // Comprimento: 400 dots = 50mm @ 203 DPI
            $zpl .= "^LS0\n"; // Ajuste de posição vertical
            $zpl .= "^LH0,0\n"; // Posição de origem
            $zpl .= "^JMA\n"; // Justificar margem automática
            $zpl .= "^JUS\n"; // Justificar para cima
            $zpl .= "^BY2,3,50\n"; // Configuração de código de barras (se necessário)

            // Se for modo teste, adicionar indicação
            if ($etiqueta['modoTeste']) {
                $zpl .= "^FO20,10^A0N,35,35^FD** TESTE **^FS\n";
                $yStart = 50;
            } else {
                $yStart = 20;
            }

            $yPos = $yStart;
            $fontSize = 40; // Fonte ainda maior para melhor legibilidade
            $lineHeight = 52; // Espaçamento ainda maior entre linhas para melhor legibilidade
            $xStart = 15; // Margem esquerda mínima
            $textWidth = 770; // Largura máxima do campo de texto (800 - 30 de margens)

            // Setor - no topo da etiqueta
            $setorText = "Setor: " . substr($etiqueta['setor'], 0, 50);
            $zpl .= "^FO{$xStart},{$yPos}^FB{$textWidth},1,0,L^A0N,{$fontSize},{$fontSize}^FD{$setorText}^FS\n";
            $yPos += $lineHeight;

            // Data - usando campo de texto com largura máxima
            $dataText = "Data: {$etiqueta['data']}";
            $zpl .= "^FO{$xStart},{$yPos}^FB{$textWidth},1,0,L^A0N,{$fontSize},{$fontSize}^FD{$dataText}^FS\n";
            $yPos += $lineHeight;

            // Motorista - texto completo usando campo com largura máxima
            $motoristaText = "Motorista: " . substr($etiqueta['motorista'], 0, 50);
            $zpl .= "^FO{$xStart},{$yPos}^FB{$textWidth},1,0,L^A0N,{$fontSize},{$fontSize}^FD{$motoristaText}^FS\n";
            $yPos += $lineHeight;

            // Cliente - texto completo usando campo com largura máxima
            $clienteText = "Cliente: " . substr($etiqueta['cliente'], 0, 50);
            $zpl .= "^FO{$xStart},{$yPos}^FB{$textWidth},1,0,L^A0N,{$fontSize},{$fontSize}^FD{$clienteText}^FS\n";
            $yPos += $lineHeight;

            // Produto - texto completo usando campo com largura máxima
            $produtoText = "Produto: " . substr($etiqueta['produto'], 0, 50);
            $zpl .= "^FO{$xStart},{$yPos}^FB{$textWidth},1,0,L^A0N,{$fontSize},{$fontSize}^FD{$produtoText}^FS\n";
            $yPos += $lineHeight;

            // Quantidade (sem pedido)
            $qtdText = "Quantidade: {$etiqueta['quantidade']}";
            $zpl .= "^FO{$xStart},{$yPos}^FB{$textWidth},1,0,L^A0N,{$fontSize},{$fontSize}^FD{$qtdText}^FS\n";

            // Finalizar etiqueta
            $zpl .= "^PQ1,0,1,Y^XZ\n";

            // Adicionar quebra entre etiquetas (exceto para a última)
            if ($index < count($etiquetas) - 1) {
                $zpl .= "\n";
            }
        }

        return $zpl;
    }
    public function relatorioMotoristaIndex()
    {
        try {

            return view('relatorios.motorista');
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível abrir os relatórios!' . $e->getMessage()]]);
        }
    }

    public function motoristaEntrega(Request $request) {}
    public function motoristaEntregaIndex()
    {
        $motoristas = request()->query('motoristas');
        $search = request()->query('search', '');
        $status = request()->query('status', '');
        $paginacao = request()->query('paginacao', 50);

        if (empty($motoristas)) {
            $pedidos = Pedido::where('id', 0)->paginate($paginacao);
            $motoristas = Motorista::select(['nome', 'id'])->get();
            return view('motorista.entrega', compact('pedidos', 'motoristas'));
        }

        $dtInicial = Carbon::now()->startOfDay();
        $dtFinal = Carbon::now()->endOfDay();

        $pedidos = Pedido::with(['cliente'])
            ->when(!empty($motoristas), function ($query) use ($motoristas) {
                $query->whereHas('motorista', function ($queryMotora) use ($motoristas) {
                    $queryMotora->whereIn('id', $motoristas);
                });
            })
            ->whereBetween('dt_previsao', [$dtInicial, $dtFinal])
            ->when(!empty($search), function ($query) use ($search) {
                $query->whereHas('cliente', function ($queryCliente) use ($search) {
                    $queryCliente->where(DB::raw('lower(name)'), 'like', '%' . strtolower($search) . '%');
                });
            })
            ->when($status !== '' && $status !== '-1', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->paginate($paginacao);
        $motoristas = Motorista::select(['nome', 'id'])->get();
        return view('motorista.entrega', compact('pedidos', 'motoristas'));
    }
}
