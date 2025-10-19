<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producao;
use App\Models\Produto;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProducaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $producaosPendentes = Producao::where('status', false)->with(['produto'])
            ->paginate(request()->paginacao ?? 30);

        $producaosConcluidas = Producao::where('status', true)->with(['produto'])
            ->paginate(request()->paginacao ?? 30);
        return view('producao.index', compact('producaosPendentes', 'producaosConcluidas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::with(['produtos'])->get();
        return view('producao.form', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $producaos = [];
            $linhasProcessadas = 0;

            // Processar os arrays do formulário
            if ($request->has('produto_id') && is_array($request->produto_id)) {
                foreach ($request->produto_id as $index => $produtoId) {
                    // Pular se o produto_id for nulo ou vazio
                    if (empty($produtoId)) {
                        continue;
                    }

                    // Validar se todos os campos necessários existem
                    if (!isset($request->quantidade[$index]) || !isset($request->data_inicio[$index])) {
                        continue;
                    }

                    $quantidade = $request->quantidade[$index];
                    $dataInicio = $request->data_inicio[$index];

                    // Validar dados básicos
                    if (empty($quantidade) || empty($dataInicio)) {
                        continue;
                    }

                    $producaos[] = [
                        'produto_id' => $produtoId,
                        'quantidade' => (int) $quantidade,
                        'data_inicio' => $dataInicio
                    ];

                    $linhasProcessadas++;
                }
            }

            // Validar se há produções para cadastrar
            if (empty($producaos)) {
                return back()->with('messages', ['error' => ['Nenhuma produção válida para cadastrar!']])->withInput($request->all());
            }

            // Validar cada produção individualmente
            foreach ($producaos as $producao) {
                $validator = Validator::make($producao, [
                    'produto_id' => 'required|exists:produtos,id',
                    'quantidade' => 'required|integer|min:1',
                    'data_inicio' => 'required|date_format:d/m/Y H:i'
                ]);

                if ($validator->fails()) {
                    throw new Exception('Dados inválidos: ' . $validator->errors()->first());
                }
            }

            // Cadastrar as produções
            foreach ($producaos as $producao) {
                Producao::create([
                    'produto_id' => $producao['produto_id'],
                    'quantidade' => $producao['quantidade'],
                    'dt_inicio' => Carbon::createFromFormat('d/m/Y H:i', $producao['data_inicio']),
                ]);
            }

            DB::commit();
            return redirect(route('producao.index'))->with('messages', ['success' => ['Produção cadastrada com sucesso! ' . $linhasProcessadas . ' linha(s) processada(s).']]);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('messages', ['error' => ['Não foi possível cadastrar a produção! ' . $e->getMessage()]])->withInput($request->all());
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
