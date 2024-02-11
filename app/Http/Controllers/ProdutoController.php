<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdutoRequest;
use App\Models\Categoria;
use App\Models\Fornecedor;
use App\Models\Marca;
use App\Models\Produto;
use App\Repositories\ProdutoRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    private ProdutoRepository $produtoRepository;

    public function __construct(ProdutoRepository $produtoRepository){
        $this->produtoRepository = $produtoRepository;
    }
    public function index() : View|RedirectResponse
    {
 
            $produtos = $this->produtoRepository->getIndex();
            return view('produto.index', compact('produtos'));
      
    }

    public function create() : View|RedirectResponse
    {
        
            $categorias = Categoria::orderBy('nome')->get();
            $marcas = Marca::orderBy('nome')->get();
            $fornecedores = Fornecedor::orderBy('nome')->get();
            return view('produto.form', compact('categorias', 'marcas', 'fornecedores'));
      
       
    }

    public function store(ProdutoRequest $request) : RedirectResponse
    {
        try {
            $this->produtoRepository->store($request);
            return redirect(route('produto.index'))->with('messages', ['success' => ['Produto criado com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível salvar o produto. Tente novamente mais tarde!']])->withInput($request->all());
        }
    }

    public function show(int $produto_id) : JsonResponse
    {
        try 
        {
            $produto = $this->produtoRepository->getProduto($produto_id);
            return response()->json(['success' => true, 'data' => $produto], 200);
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['success' => false, 'data' => null, 'message' => 'Produto não encontrado'], 400);
            }
            return response()->json(['success' => false, 'data' => null, 'message' => 'Erro ao processar requisição. Tente novamente mais tarde.'], 500);
        }
    }

    public function edit($id) : View|RedirectResponse
    {
        $categorias = Categoria::orderBy('nome')->get();
        $marcas = Marca::orderBy('nome')->get();
        $fornecedores = Fornecedor::orderBy('nome')->get();
        try {
            $produto = Produto::findOrFail($id);
            return view('produto.form', compact('produto', 'categorias', 'marcas', 'fornecedores'));
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível encontrar o produto!']]);
        }
    }

    public function update(ProdutoRequest $request, int $id) : RedirectResponse
    {
        try {
            $this->produtoRepository->update($request, $id);
            return redirect(route('produto.index'))->with('messages', ['success' => ['Produto atualizado com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível atualizar o produto!']])->withInput($request->all());
        }
    }

    public function destroy(int $id) : RedirectResponse
    {
        try {
            $this->produtoRepository->destroy($id);
            return back()->with('messages', ['success' => ['Produto excluído com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível excluír o produto!']]);
        }
    }

    public function ativar(int $categoria_id){
        try {
            $this->produtoRepository->ativar($categoria_id);
            return back()->with('messages', ['success' => ['Categoria ativada com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível ativar a categoria!'.$e->getMessage()]]);
        }
    }
}
