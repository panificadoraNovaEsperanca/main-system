<?php

namespace App\Http\Controllers;

use App\Http\Requests\MotoristaRequest;
use App\Http\Requests\RelatorioMotorista;
use App\Models\Motorista;
use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MotoristaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $motoristas = Motorista::withTrashed()->paginate(request()->paginacao ?? 10);
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
            return back()->with('messages', ['error' => ['Não foi possível excluír o produto!']]);
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

            $motorista = Motorista::findOrFail($request->motorista);
            $inicio = Carbon::createFromFormat('d/m/Y', $request->data)->startOfDay();
            $fim = Carbon::createFromFormat('d/m/Y', $request->data)->endOfDay();

            $pedidos = Pedido::where('motorista_id', $motorista->id)
                ->whereBetween('dt_previsao', [$inicio, $fim])
                ->with(['produtos', 'cliente'])->orderBy('dt_previsao','ASC')->get();
            $pdf =  Pdf::loadView('relatorios.pdf.motorista', [
                'motorista' => $motorista,
                'pedidos' => $pedidos,
                'dia' => $inicio->format('d/m/Y')
            ]);
            return $pdf->download("Relatório {$motorista->nome}.pdf");
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'data' => null, 'message' => 'Erro ao processar requisição. Tente novamente mais tarde.' . $e->getMessage()], 400);
        }
    }
    public function relatorioMotoristaIndex()
    {
        try {

            return view('relatorios.motorista');
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível abrir os relatórios!' . $e->getMessage()]]);
        }
    }
}
