<?php

namespace App\Http\Controllers;

use App\DataTables\AgendamentosDataTable;
use App\Models\Categoria;
use App\Models\Produto;
use App\Repositories\CategoriaRepository;
use App\Repositories\ProdutoRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View|RedirectResponse
    {

        try {
            $categoriaRepository = new CategoriaRepository(new Categoria());
            $categorias = $categoriaRepository->getIndexHome();
            return view('home', compact('categorias'));
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível acessar a página principal!']]);

        }
    }

    public function produtosCategoriaEmEstoque(int $categoria_id): View|RedirectResponse
    {

        try {
            $produtoRepository = new ProdutoRepository(new Produto());
            $produtosCategoria = $produtoRepository->getIndexHome($categoria_id);
            $categoria = Categoria::findOrFail($categoria_id);
            return view('produtosCategoria.index', compact('produtosCategoria', 'categoria'));
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível encontrar a categoria!']]);

        }
    }
}
