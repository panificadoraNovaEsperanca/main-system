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
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pedidos = Pedido::with(['motorista', 'cliente'])
            ->when(request()->search != '', function ($query) {
                $query->whereHas('motorista', function ($queryMotora) {
                    $queryMotora->where(DB::raw('lower(nome)'), 'like', '%' . strtolower(request()->search) . '%');
                })->orWhereHas('cliente', function ($queryCliente) {
                    $queryCliente->where(DB::raw('lower(name)'), 'like', '%' . strtolower(request()->search) . '%');
                });
            })
            ->when(request()->status != '' && request()->status != '-1', function ($query) {
                $query->where('status', request()->status);
            })
            ->when(request()->dataHora != '', function ($query) {
                $dtInicial = Carbon::createFromFormat('d/m/Y', request()->dataHora)->startOfDay();
                $dtFinal = Carbon::createFromFormat('d/m/Y', request()->dataHora)->endOfDay();
                $query->whereBetween('dt_previsao', [$dtInicial, $dtFinal]);
            })
            ->paginate(request()->paginacao ?? 10);
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
            DB::beginTransaction();
            $cliente = Cliente::findOrFail($request->cliente_id);
            if ($cliente->tipo_cliente == null) {
                return back()->with('messages', ['error' => ['Cadastro de cliente incompleto! Finalize o cadastro de cliente para completar o pedido']])->withInput($request->all());;
            }
            $dataPedido = Carbon::createFromFormat('d/m/Y H:i', $request->dataHora);
       
            $pedido = Pedido::create([
                'cliente_id' => $cliente->id,
                'motorista_id' => $request->motorista,
                'dt_previsao' => $dataPedido,
                'status' => $request->status,
            ]);
            foreach ($request->produto as $linha => $valor) {
                $preco = 0;
                if($cliente->tipo_cliente == 'h'){
                    $preco = $request->precoProduto[$linha];
                }else{
                    $preco = Produto::findOrFail($valor)->precos[$cliente->tipo_cliente];
                }
                PedidoProduto::create([
                    'pedido_id' => $pedido->id,
                    'produto_id' => $valor,
                    'quantidade' => $request->quantidade[$linha],
                    'observacao' => $request->observacao[$linha] ?? '',
                    'preco' => $preco
                ]);
            }
            if ($request->repete) {
                $datas = explode(',', $request->periodo);
                $horaPedido = $dataPedido->format('H');
                $minutoPedido = $dataPedido->format('i');
                foreach($datas as $data) {
                    $data = Carbon::createFromFormat('d/m/Y H:i', $data . " {$horaPedido}:{$minutoPedido}" );
                    $novoPedido = Pedido::create([
                        'cliente_id' => $cliente->id,
                        'motorista_id' => $request->motorista,
                        'dt_previsao' => $data,
                        'status' => 'AGENDADO',

                    ]);
                    foreach ($request->produto as $linha => $valor) {
                        $preco = 0;
                        if($cliente->tipo_cliente == 'h'){
                            $preco = $request->precoProduto[$linha];
                        }else{

                            $preco = Produto::findOrFail($valor)->precos[$cliente->tipo_cliente];
                        }
                        PedidoProduto::create([
                            'pedido_id' => $novoPedido->id,
                            'produto_id' => $valor,
                            'quantidade' => $request->quantidade[$linha],
                            'observacao' => $request->observacao[$linha] ?? '',
                            'preco' => $preco
                        ]);
                    }
                }
            }
            DB::commit();
            return redirect(route('pedido.index'))->with('messages', ['success' => ['Pedido cadastrado com sucesso!']]);
        } catch (\Exception $e) {
            DB::rollBack();
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
            dd($e);
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
                $preco = 0;
                if($cliente->tipo_cliente == 'h'){
                    $preco = $request->precoProduto[$linha];
                }else{
                    $preco = Produto::findOrFail($valor)->precos[$cliente->tipo_cliente];
                }
                PedidoProduto::updateOrCreate(['pedido_id' => $id, 'produto_id' => $valor], [
                    'pedido_id' => $id,
                    'produto_id' => $valor,
                    'quantidade' => $request->quantidade[$linha],
                    'observacao' => $request->observacao[$linha] ?? '',
                    'preco' => $preco
                ]);
            }

            if ($request->repete) {
                $datas = explode(',', $request->periodo);
                
                foreach($datas as $data) {
                    $data = Carbon::createFromFormat('d/m/Y H:i', $data . ' ' . $request->horario);
                    $novoPedido = Pedido::create([
                        'cliente_id' => $cliente->id,
                        'motorista_id' => $request->motorista,
                        'dt_previsao' => $data,
                        'status' => $request->status,
                    ]);
                    foreach ($request->produto as $linha => $valor) {
                        $preco = 0;
                        if($cliente->tipo_cliente == 'h'){
                            $preco = $request->precoProduto[$linha];
                        }else{

                            $preco = Produto::findOrFail($valor)->precos[$cliente->tipo_cliente];
                        }
                        PedidoProduto::create([
                            'pedido_id' => $novoPedido->id,
                            'produto_id' => $valor,
                            'quantidade' => $request->quantidade[$linha],
                            'observacao' => $request->observacao[$linha] ?? '',
                            'preco' => $preco
                        ]);
                    }
                }
            }   
            return redirect(route('pedido.index'))->with('messages', ['success' => ['Pedido editado com sucesso!']]);
        } catch (\Exception $e) {
            dd($request->all(),$e);
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
            PedidoProduto::where('pedido_id', $id)->delete();
            Pedido::findOrFail($id)->delete();
            return redirect(route('pedido.index'))->with('messages', ['success' => ['Pedido excluído com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não excluir o pedido!']]);
        }
    }

    public function baixaPedido()
    {
        $pedidos = Pedido::with(['motorista', 'cliente'])
            ->when(request()->search != '', function ($query) {
                $query->whereHas('motorista', function ($queryMotora) {
                    $queryMotora->where(DB::raw('lower(nome)'), 'like', '%' . strtolower(request()->search) . '%');
                })->orWhereHas('cliente', function ($queryCliente) {
                    $queryCliente->where(DB::raw('lower(name)'), 'like', '%' . strtolower(request()->search) . '%');
                });
            })
            ->when(request()->status != '' && request()->status != '-1', function ($query) {
                $query->where('status', request()->status);
            })
            ->when(request()->dataHora != '', function ($query) {
                $dtInicial = Carbon::createFromFormat('d/m/Y', request()->dataHora)->startOfDay();
                $dtFinal = Carbon::createFromFormat('d/m/Y', request()->dataHora)->endOfDay();
                $query->whereBetween('dt_previsao', [$dtInicial, $dtFinal]);
            })
            ->paginate(request()->paginacao ?? 50);

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
    public function pedidosDelete(Request $request)
    {
        try {

            foreach($request->pedidos as $pedido_id){
                PedidoProduto::where('pedido_id','=',$pedido_id)->delete();
                Pedido::where('id','=',$pedido_id)->delete();
            }
            return response()->json(['success' => true, 'data' => '','message' => 'Pedidos excluídos com sucesso!'], 200);
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'data' => null, 'message' => 'Erro ao processar requisição. Tente novamente mais tarde.' . $e->getMessage()], 400);
        }
    }


    public function atualizarPedidos(Request $request)
    {
        try {

            foreach ($request->pedido as $pedido_id) {
                Pedido::findOrFail($pedido_id)->update(['status' => $request->status]);
            }
            return redirect(route('pedido.atualiza'))->with('messages', ['success' => ['Pedidos atualizados com sucesso!']]);
        } catch (\Exception $e) {
            return redirect(route('pedido.atualiza'))->with('messages', ['success' => ['Não foi possível atualizar os pedidos']]);
        }
    }

}
