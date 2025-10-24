<?php

namespace App\Http\Controllers;

use App\Http\Requests\InsumoRequest;
use App\Http\Requests\ProdutoRequest;
use App\Http\Requests\RelatorioProduto;
use App\Models\Categoria;
use App\Models\Fornecedor;
use App\Models\Insumo;
use App\Models\Marca;
use App\Models\Pedido;
use App\Models\PedidoProduto;
use App\Models\Producao;
use App\Models\Produto;
use App\Repositories\ProdutoRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class InsumoController extends Controller
{

    public function index(): View|RedirectResponse
    {

        $insumos = Insumo::withTrashed()->when(request()->search != '', function ($query) {
            $query->where(DB::raw('lower(nome)'), 'ilike', '%' . request()->search . '%');
        })
            ->orderBy('id', 'asc')
            ->paginate(request()->paginacao ?? 10);
        return view('insumo.index', compact('insumos'));
    }

    public function create(): View|RedirectResponse
    {
        return view('insumo.form');
    }

    public function store(InsumoRequest $request): RedirectResponse
    {
        try {
            Insumo::create([
                'nome' => $request->nome,
                'unidade_medida' => $request->unidade_medida,
                'quantidade_atual' => 0,
                'quantidade_minima' => $request->quantidade_minima,
            ]);
            return redirect(route('insumo.index'))->with('messages', ['success' => ['Produto criado com sucesso!']]);
        } catch (ValidationException $e) {
            return back()
                ->with('messages', ['error' => $e->errors()])
                ->withInput();
        } catch (\Exception $e) {

            return back()->with(
                'messages',
                ['error' => ['Não foi possível cadastrar o insumo! ' . $e->getMessage()]]
            )->withInput();
        }
    }

    public function show(int $produto_id)
    {

    }

    public function edit($id): View|RedirectResponse
    {

        try {

            $insumo = Insumo::findOrFail($id);
            return view('insumo.form', compact('insumo'));
        } catch (\Exception $e) {
            Log::info(json_encode($e, true));
            return back()->with('messages', ['error' => ['Não foi possível encontrar o insumo!']]);
        }
    }

    public function update(InsumoRequest $request, int $id): RedirectResponse
    {
        try {
            Insumo::findOrFail($id)->update([
                'nome' => $request->nome,
                'unidade_medida' => $request->unidade_medida,
                'quantidade_atual' => $request->quantidade_atual,
                'quantidade_minima' => $request->quantidade_minima,
            ]);
            return redirect(route('insumo.index'))->with('messages', ['success' => ['Produto atualizado com sucesso!']]);
        } catch (\Exception $e) {
            dd($e);
            return back()->with('messages', ['error' => ['Não foi possível atualizar o produto!']])->withInput($request->all());
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            Insumo::findOrFail($id)->delete();
            return back()->with('messages', ['success' => ['Insumo excluído com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível excluír o insumo!']]);
        }
    }

    public function ativar(int $insumo_id)
    {
        try {
            Insumo::withTrashed()->where('id', $insumo_id)->update(['deleted_at' => null]);
            return back()->with('messages', ['success' => ['Insumo ativado com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível ativar o Insumo!' . $e->getMessage()]]);
        }
    }
}
