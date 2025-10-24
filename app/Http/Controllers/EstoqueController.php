<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstoqueRequest;
use App\Mail\InsumoAbaixoDoMinimoMail;
use App\Models\Estoque;
use App\Models\Insumo;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class EstoqueController extends Controller
{

    public function index(): View|RedirectResponse
    {

        $estoques = Estoque::when(request()->search != '', function ($query) {
            $query->where('id', 'ilike', '%' . request()->search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(request()->paginacao ?? 10);

        $insumos = Insumo::orderBy('quantidade_atual', 'asc')
            ->paginate(request()->paginacao ?? 10);
        return view('estoque.index', compact('estoques','insumos'));
    }

    public function create(): View|RedirectResponse
    {
        $insumos = Insumo::all();

        return view('estoque.form', compact('insumos'));
    }


    public function store(EstoqueRequest $request): RedirectResponse
    {
        try {

            DB::transaction(function () use ($request) {
                $insumo = Insumo::where('id', $request->insumo_id)->lockForUpdate()->firstOrFail();

                if ($request->tipo === 'entrada') {
                    $insumo->quantidade_atual += $request->valor;
                } else {
                    $insumo->quantidade_atual = max(0, $insumo->quantidade_atual - $request->valor);
                }

                $insumo->save();
                if ($request->tipo === 'saida' && $insumo->quantidade_atual < $insumo->quantidade_minima) {
                    Mail::to('heryckmota@gmail.com')
                        ->send(new InsumoAbaixoDoMinimoMail($insumo));
                }
                Estoque::create([
                    'insumo_id' => $request->insumo_id,
                    'tipo'      => $request->tipo,
                    'valor'     => $request->valor,
                    'descricao' => $request->descricao,
                    'user_id'   => Auth::id(),
                ]);
            });

            return redirect(route('estoque.index'))
                ->with('messages', ['success' => ['Movimentação registrada com sucesso!']]);
        } catch (ValidationException $e) {
            return back()->with('messages', ['error' => $e->errors()])->withInput();
        } catch (\Exception $e) {
            return back()->with('messages', [
                'error' => ['Não foi possível cadastrar a transação! ' . $e->getMessage()]
            ])->withInput();
        }
    }
}
