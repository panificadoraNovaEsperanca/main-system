<?php

namespace App\Http\Controllers;

use App\Http\Requests\PedidoRequest;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\PedidoProduto;
use App\Models\Produto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pedidos = Pedido::with(['motorista', 'cliente'])->paginate(request()->paginacao ?? 10);
        return view('pedido.index', compact('pedidos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $produtos = Produto::get();
        return view('pedido.form', compact('produtos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PedidoRequest $request)
    {


        try {
            $cliente = Cliente::findOrFail($request->cliente_id);
            $dataPedido = Carbon::createFromFormat('d/m/Y H:i', $request->dataHora);
            $pedido = Pedido::create([
                'cliente_id' => $cliente->id,
                'motorista_id' => $request->motorista,
                'dt_previsao' => $dataPedido,
                'status' => $request->status,
            ]);
            foreach ($request->produto as $linha => $valor) {
                $preco = Produto::findOrFail($valor)->precos[$cliente->tipo_cliente];
                PedidoProduto::create([
                    'pedido_id' => $pedido->id,
                    'produto_id' => $valor,
                    'quantidade' => $request->quantidade[$linha],
                    'preco' => $preco
                ]);
            }
            if ($request->repete) {
                $datas = explode(' - ', $request->periodo);
                $dataInicial = Carbon::createFromFormat('d/m/Y', $datas[0]);
                $dataFinal = Carbon::createFromFormat('d/m/Y', $datas[1]);
                for ($data = $dataInicial; $data->lte($dataFinal); $data->addDay()) {
                    $novoPedido = Pedido::create([
                        'cliente_id' => $cliente->id,
                        'motorista_id' => $request->motorista,
                        'dt_previsao' => Carbon::createFromFormat('d/m/Y H:i', $data->format('d/m/Y') . ' ' . $dataPedido->format('H:i')),
                        'status' => $request->status,
                    ]);
                    foreach ($request->produto as $linha => $valor) {
                        $preco = Produto::findOrFail($valor)->precos[$cliente->tipo_cliente];
                        PedidoProduto::create([
                            'pedido_id' => $novoPedido->id,
                            'produto_id' => $valor,
                            'quantidade' => $request->quantidade[$linha],
                            'preco' => $preco
                        ]);
                    }
                }
            }
            return redirect(route('pedido.index'))->with('messages', ['success' => ['Pedido cadastrado com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível cadastrar o pedido!']])->withInput($request->all());;
        }
    }


    public function show($id)
    {
        try {

            $pedido = Pedido::with(['motorista', 'produtos', 'cliente'])->where('id', $id)->first();
            return response()->json(['success' => true, 'data' => $pedido], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'data' => null, 'message' => 'Erro ao processar requisição. Tente novamente mais tarde.' . $e->getMessage()], 400);
        }
    }


    public function edit($id)
    {
        try {
            $produtos = Produto::get();
            $pedido = Pedido::with(['motorista', 'produtos', 'cliente'])->where('id', $id)->first();

            return view('pedido.form', compact('pedido', 'produtos'));
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível editar o pedido!']]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PedidoRequest $request, $id)
    {
        try {
            $cliente = Cliente::findOrFail($request->cliente_id);
            $dataPedido = Carbon::createFromFormat('d/m/Y H:i', $request->dataHora);
            Pedido::findOrFail($id)->update([
                'cliente_id' => $cliente->id,
                'motorista_id' => $request->motorista,
                'dt_previsao' => $dataPedido,
                'status' => $request->status,
            ]);

            foreach ($request->produto as $linha => $valor) {
                $preco = Produto::findOrFail($valor)->precos[$cliente->tipo_cliente];
                PedidoProduto::updateOrCreate(['pedido_id' => $id, 'produto_id' => $valor], [
                    'pedido_id' => $id,
                    'produto_id' => $valor,
                    'quantidade' => $request->quantidade[$linha],
                    'preco' => $preco
                ]);
            }

            if ($request->repete) {
                $datas = explode(' - ', $request->periodo);
                $dataInicial = Carbon::createFromFormat('d/m/Y', $datas[0]);
                $dataFinal = Carbon::createFromFormat('d/m/Y', $datas[1]);
                for ($data = $dataInicial; $data->lte($dataFinal); $data->addDay()) {
                    $novoPedido = Pedido::create([
                        'cliente_id' => $cliente->id,
                        'motorista_id' => $request->motorista,
                        'dt_previsao' => Carbon::createFromFormat('d/m/Y H:i', $data->format('d/m/Y') . ' ' . $dataPedido->format('H:i')),
                        'status' => $request->status,
                    ]);
                    foreach ($request->produto as $linha => $valor) {
                        $preco = Produto::findOrFail($valor)->precos[$cliente->tipo_cliente];
                        PedidoProduto::create([
                            'pedido_id' => $novoPedido->id,
                            'produto_id' => $valor,
                            'quantidade' => $request->quantidade[$linha],
                            'preco' => $preco
                        ]);
                    }
                }
            }
            return redirect(route('pedido.index'))->with('messages', ['success' => ['Pedido editado com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível cadastrar o pedido!']])->withInput($request->all());;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            PedidoProduto::where('produto_id', $id)->delete();
            Pedido::findOrFail($id)->delete();
            return redirect(route('pedido.index'))->with('messages', ['success' => ['Pedido excluído com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não excluir o pedido!']]);
        }
    }

    public function baixaPedido()
    {
        $pedidos = Pedido::whereNot('status', 'ENTREGUE')->with(['motorista', 'cliente'])->paginate(request()->paginacao ?? 10);

        return view('pedido.atualizador', compact('pedidos'));
    }
    public function getPedidoBaixa($pedido_id)
    {
        try {

            $pedido = Pedido::with(['motorista', 'produtos', 'cliente'])
                ->where('id', $pedido_id)->first();
            if ($pedido == null) {
                return response()->json(['success' => false, 'data' => null, 'message' => 'Pedido não encontrado']);
            }
            return response()->json(['success' => true, 'data' => $pedido], 200);
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'data' => null, 'message' => 'Erro ao processar requisição. Tente novamente mais tarde.' . $e->getMessage()], 400);
        }
    }


    public function atualizaPedido(Request $request, $pedido_id)
    {
        try {

            Pedido::findOrFail($pedido_id)->update(['status' => $request->status]);
            return response()->json(['success' => true, 'data' => '', 'message' => 'Pedido atualizado com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'data' => null, 'message' => 'Erro ao processar requisição. Tente novamente mais tarde.' . $e->getMessage()], 400);
        }
    }
}
