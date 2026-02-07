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
        $dataFiltro = $this->resolveDataFiltro();

        $inicio = $dataFiltro['inicio'];
        $fim = $dataFiltro['fim'];
        $dataFiltroFormatada = $dataFiltro['formatada'];
        
        dump('=== DEBUG FILTRO DINÂMICO ===');
        dump([
            'inicio_carbon' => $inicio,
            'inicio_timezone' => $inicio->timezone->getName(),
            'inicio_toDateTimeString' => $inicio->toDateTimeString(),
            'inicio_toIso8601String' => $inicio->toIso8601String(),
            'inicio_format' => $inicio->format('Y-m-d H:i:s'),
            'fim_carbon' => $fim,
            'fim_timezone' => $fim->timezone->getName(),
            'fim_toDateTimeString' => $fim->toDateTimeString(),
            'fim_toIso8601String' => $fim->toIso8601String(),
            'fim_format' => $fim->format('Y-m-d H:i:s'),
        ]);
        
        // Teste com filtro manual que funciona
        dump('=== TESTE FILTRO MANUAL (que funciona) ===');
        $producaosManual = Producao::with(['produto', 'produto.categoria', 'user'])
            ->whereBetween('dt_inicio', [
                '2026-02-04 17:00:00',
                '2026-02-04 19:04:00'
            ])
            ->get();
        dump('Total manual: ' . $producaosManual->count());
        if ($producaosManual->count() > 0) {
            dump('Primeiro manual dt_inicio: ' . $producaosManual->first()->dt_inicio);
        }
        
        // Query com filtro dinâmico
        $query = Producao::with(['produto', 'produto.categoria', 'user'])
            ->whereBetween('dt_inicio', [$inicio, $fim]);
        
        dump('=== SQL GERADA ===');
        dump($query->toSql());
        dump($query->getBindings());
        
        $producaos = $query->paginate(request()->query('paginacao', 100));
        
        dump('=== RESULTADOS FILTRO DINÂMICO ===');
        dump('Total encontrado: ' . $producaos->total());
        dump('Count items: ' . $producaos->count());
        
        // Verificar alguns registros do banco na data
        dump('=== REGISTROS NO BANCO (2026-02-04) ===');
        $registrosBanco = DB::table('producaos')
            ->whereDate('dt_inicio', '2026-02-04')
            ->select('id', 'dt_inicio', 'produto_id')
            ->limit(10)
            ->get();
        dump($registrosBanco);
        
        $producaoCategoria = [];

        foreach ($producaos as $producao) {
            $producaoCategoria[$producao->produto->categoria->nome][] = $producao;
        }
        
        dd([
            'producaoCategoria' => $producaoCategoria,
            'dataFiltroFormatada' => $dataFiltroFormatada,
            'total' => $producaos->total(),
        ]);
        
        return view('producaoBaixa.index', compact('producaoCategoria', 'dataFiltroFormatada'));
    }

    /**
     * Resolve a data usada no filtro: query 'data' (d/m/Y) ou hoje.
     * Em caso de data inválida, usa o dia atual.
     */
    private function resolveDataFiltro(): array
    {
        $dataQuery = request()->query('data');

        if (!empty($dataQuery)) {
            try {
                $carbon = Carbon::createFromFormat('d/m/Y', $dataQuery);
                return [
                    'inicio' => $carbon->copy()->startOfDay(),
                    'fim' => $carbon->copy()->endOfDay(),
                    'formatada' => $carbon->format('d/m/Y'),
                ];
            } catch (\Exception $e) {
                // Data inválida: cair no comportamento "hoje"
            }
        }

        $hoje = Carbon::now();
        return [
            'inicio' => $hoje->copy()->startOfDay(),
            'fim' => $hoje->copy()->endOfDay(),
            'formatada' => $hoje->format('d/m/Y'),
        ];
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
