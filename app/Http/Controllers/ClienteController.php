<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Http\Requests\RelatorioCliente;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Repositories\ClienteRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    private ClienteRepository $clienteRepository;

    public function __construct(ClienteRepository $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
    }
    public function index()
    {
        $clientes = $this->clienteRepository->getAll();
        return view('cliente.index', compact('clientes'));
    }

    public function create()
    {
        return view('cliente.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClienteRequest $request)
    {
        try {
            Cliente::create($request->except('_token'));
            return redirect(route('cliente.index'))->with('messages', ['success' => ['Cliente cadastrado com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível cadastrar o cliente!']])->withInput($request->all());;
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
            $cliente = Cliente::findOrFail($id);
            return view('cliente.form', compact('cliente'));
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possícel editar a cliente!']]);
        }
    }
    public function clientsByName()
    {
        try {
            $name = request()->query('nome');
            if ($name == '') {

                return response()->json(['success' => true, 'data' => []], 200);
            }
            $resultados = Cliente::where(DB::raw('LOWER(name)'), 'LIKE', '%' . strtolower($name) . '%')->select(['name', 'id', 'tipo_cliente'])->get();
            return response()->json(['success' => true, 'data' => $resultados], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'data' => null, 'message' => 'Erro ao processar requisição. Tente novamente mais tarde.' . $e->getMessage()], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClienteRequest $request, $id)
    {
        try {
            $cliente = Cliente::findOrFail($id)->update($request->except(['_token', '_method']));
            return redirect(route('cliente.index'))->with('messages', ['success' => ['Cliente atualizada com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possícel editar a cliente!']]);
        }
    }

    /**
     * 
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Cliente::findOrFail($id)->delete();
            return redirect(route('cliente.index'))->with('messages', ['success' => ['Cliente desativado com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possícel editar a cliente!']]);
        }
    }

    public function ativar(int $id)
    {
        try {
            Cliente::withTrashed()->where('id', $id)->update(['deleted_at' => null]);
            return back()->with('messages', ['success' => ['Cliente ativado com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível ativar a categoria!' . $e->getMessage()]]);
        }
    }

    public function relatorioCliente(RelatorioCliente $request)
    {
        try {

            $cliente = Cliente::findOrFail($request->cliente);
            $datas = explode(' - ', $request->intervalo);

            $inicio = Carbon::createFromFormat('d/m/Y', $datas[0])->startOfDay();

            $fim = Carbon::createFromFormat('d/m/Y', $datas[1])->endOfDay();
            $pedidos = Pedido::where('cliente_id', $cliente->id)
                ->whereBetween('dt_previsao', [$inicio, $fim])
                ->when($request->status != '-1', function ($query) use ($request) {
                    $query->where('status', $request->status);
                })
                ->with(['produtos'])->orderBy('dt_previsao', 'ASC')->get();
            $pdf =  Pdf::loadView('relatorios.pdf.clientes', [
                'cliente' => $cliente,
                'pedidos' => $pedidos,
                'inicio' => $inicio,
                'fim' => $fim
            ]);
            return $pdf->download("Relatório {$cliente->name}.pdf");
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'data' => null, 'message' => 'Erro ao processar requisição. Tente novamente mais tarde.' . $e->getMessage()], 400);
        }
    }
    public function relatorioClienteIndex()
    {
        try {
            return view('relatorios.cliente');
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível abrir os relatórios!' . $e->getMessage()]]);
        }
    }
}
