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

            // Flag de teste - se true, imprime apenas 5 etiquetas
            $modoTeste = $request->get('teste', false);

            $motoristasId = Motorista::when($request->motorista != null && $request->motorista != '', function ($query) use ($request) {
                $query->where('id', $request->motorista);
            })->whereHas('pedidos', function ($query2) use ($inicio, $fim) {
                $query2->whereBetween('dt_previsao', [$inicio, $fim]);
            })->pluck('id');

            $etiquetas = [];
            $contadorEtiquetas = 0;
            $maxEtiquetas = $modoTeste ? 5 : PHP_INT_MAX;

            foreach ($motoristasId as $motorista_id) {
                $pedidos = Pedido::with(['produtos.produto', 'cliente', 'motorista'])
                    ->where('motorista_id', $motorista_id)
                    ->whereBetween('dt_previsao', [$inicio, $fim])
                    ->orderBy('dt_previsao', 'ASC')
                    ->get();

                foreach ($pedidos as $pedido) {
                    // Ordenar produtos por setor
                    $produtosOrdenados = $pedido->produtos->sortBy(function ($pedidoProduto) {
                        return $pedidoProduto->produto->setor ?? '999';
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

                            $etiquetas[] = [
                                'data' => $inicio->format('d/m/Y'),
                                'motorista' => $this->removerCaracteresEspeciais($pedido->motorista->nome),
                                'cliente' => $this->removerCaracteresEspeciais($pedido->cliente->name),
                                'produto' => $this->removerCaracteresEspeciais($produto->nome),
                                'quantidade' => $quantidadeEtiqueta,
                                'pedido' => $pedido->id,
                                'setor' => $this->removerCaracteresEspeciais($produto->setor ?? 'N/A'),
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
            // Configurações iniciais da etiqueta - Largura máxima (832 dots para 4 polegadas)
            $zpl .= "^XA\n"; // Início da etiqueta
            $zpl .= "^MMT\n"; // Modo de thermal transfer
            $zpl .= "^PW832\n"; // Largura máxima da etiqueta (832 dots = 4 polegadas)
            $zpl .= "^LL0400\n"; // Comprimento da etiqueta
            $zpl .= "^LS0\n"; // Ajuste de posição

            // Se for modo teste, adicionar indicação
            if ($etiqueta['modoTeste']) {
                $zpl .= "^FO50,20^A0N,25,25^FD** MODO TESTE **^FS\n";
                $zpl .= "^FO700,20^A0N,25,25^FD{$etiqueta['indice']}/5^FS\n";
                $yStart = 70;
            } else {
                $yStart = 50;
            }

            $yPos = $yStart;

            // Data
            $zpl .= "^FO50,{$yPos}^A0N,28,28^FDData: {$etiqueta['data']}^FS\n";
            $yPos += 40;

            // Motorista - usando campo com largura maior
            $motoristaText = "Motorista: " . substr($etiqueta['motorista'], 0, 50); // Limita tamanho
            $zpl .= "^FO50,{$yPos}^A0N,28,28^FD{$motoristaText}^FS\n";
            $yPos += 40;

            // Cliente - usando campo com largura maior
            $clienteText = "Cliente: " . substr($etiqueta['cliente'], 0, 50); // Limita tamanho
            $zpl .= "^FO50,{$yPos}^A0N,28,28^FD{$clienteText}^FS\n";
            $yPos += 40;

            // Produto - usando campo com largura maior
            $produtoText = "Produto: " . substr($etiqueta['produto'], 0, 50); // Limita tamanho
            $zpl .= "^FO50,{$yPos}^A0N,28,28^FD{$produtoText}^FS\n";
            $yPos += 40;

            // Quantidade
            $zpl .= "^FO50,{$yPos}^A0N,28,28^FDQuantidade: {$etiqueta['quantidade']}^FS\n";
            $yPos += 40;

            // Pedido
            $zpl .= "^FO50,{$yPos}^A0N,28,28^FDPedido: {$etiqueta['pedido']}^FS\n";
            $yPos += 40;

            // Setor - posicionado à direita
            $zpl .= "^FO650,{$yPos}^A0N,24,24^FDSetor: {$etiqueta['setor']}^FS\n";

            // // Código de barras com número do pedido e índice
            // $barcodeData = "{$etiqueta['pedido']}-{$etiqueta['indice']}";
            // $zpl .= "^FO50,350^B3N,,Y,N^FD{$barcodeData}^FS\n";

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
