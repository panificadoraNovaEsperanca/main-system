<?php

namespace App\Http\Controllers;

use App\Http\Requests\SetorRequest;
use App\Models\Setor;
use App\Repositories\SetorRepository;
use App\Services\SetorService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SetorController extends Controller
{
    private SetorRepository $setorRepository;

    public function __construct(SetorRepository $setorRepository)
    {
        $this->setorRepository = $setorRepository;
    }

    public function index(): View
    {
        $setores = $this->setorRepository->getIndex();
        return view('setor.index', compact('setores'));
    }

    public function create(): View
    {
        return view('setor.form');
    }

    public function store(SetorRequest $request): RedirectResponse
    {
        try {
            SetorService::store($request, $this->setorRepository);
            return redirect(route('setor.index'))->with('messages', ['success' => ['Setor criado com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível criar o setor!']])->withInput($request->all());
        }
    }

    public function show(int $setor_id): array
    {
        try {
            // Implementar se necessário
            return ['success' => true, 'data' => []];
        } catch (\Exception $e) {
            return ['success' => false, 'data' => '', 'message' => 'Não foi possível encontrar o setor', 'error' => $e->getMessage()];
        }
    }

    public function edit(int $setor_id): View|RedirectResponse
    {
        try {
            $setor = Setor::findOrFail($setor_id);
            return view('setor.form', compact('setor'));
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível editar o setor!']]);
        }
    }

    public function update(SetorRequest $request, int $setor_id): RedirectResponse
    {
        try {
            SetorService::update($request, $setor_id, $this->setorRepository);
            return redirect(route('setor.index'))->with('messages', ['success' => ['Setor atualizado com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível atualizar o setor!']])->withInput($request->all());
        }
    }

    public function destroy(int $setor_id): RedirectResponse
    {
        try {
            $this->setorRepository->destroy($setor_id);
            return back()->with('messages', ['success' => ['Setor excluído com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível excluir o setor!']]);
        }
    }

    public function ativar(int $setor_id): RedirectResponse
    {
        try {
            $this->setorRepository->ativar($setor_id);
            return back()->with('messages', ['success' => ['Setor ativado com sucesso!']]);
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível ativar o setor! ' . $e->getMessage()]]);
        }
    }
}
