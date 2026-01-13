<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Producao;
use App\Models\Produto;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class ProducaoBaixaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $producaos = Producao::with(['produto', 'produto.categoria', 'user'])
        ->when(request()->query('data'), function ($query) {
            $inicio = Carbon::createFromFormat('d/m/Y', request()->query('data'))->startOfDay();
            $fim = Carbon::createFromFormat('d/m/Y', request()->query('data'))->endOfDay();
            $query->whereBetween('dt_inicio', [$inicio, $fim]);
        })
         ->when(!request()->query('data'), function ($query) {
            $inicio = Carbon::now()->startOfDay();
            $fim = Carbon::now()->endOfDay();
            $query->whereBetween('dt_inicio', [$inicio, $fim]);
        })
        ->paginate(request()->query('paginacao', 30)); // 30 é o valor padrão
    
        $producaoCategoria = [];

        foreach($producaos as $producao){
            $producaoCategoria[$producao->produto->categoria->nome][] = $producao;
            
        }
        if(count($_GET) == 0){
            $_GET['turno'] = 'MANHÃ';
            $_GET['data'] = Carbon::now()->format('d/m/Y');
        }
        
        return view('producaoBaixa.index', compact('producaoCategoria'));
    }


    public function confirmarProducao(Request $request){
        try {
            $producao = Producao::findOrFail($request->producao_id);
            
            // Sempre usar o usuário logado
            $userId = auth()->id();
            
            $producao->update([
                'status' => !$producao->status,
                'user_id' => $userId
            ]);
            
            $mensagem = $producao->status ? 'Produção confirmada com sucesso' : 'Produção desfeita com sucesso';
            return response()->json(['success' => true, 'data' => '','message' => $mensagem], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'data' => null, 'message' => 'Erro ao processar requisição. Tente novamente mais tarde.' . $e->getMessage()], 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
