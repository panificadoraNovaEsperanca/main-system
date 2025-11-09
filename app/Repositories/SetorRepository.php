<?php

namespace App\Repositories;

use App\Http\Requests\SetorRequest;
use App\Interfaces\SetorRepositoryInterface;
use App\Models\Setor;

class SetorRepository implements SetorRepositoryInterface
{
    private Setor $setor;

    public function __construct(Setor $model)
    {
        $this->setor = $model;
    }

    public function getIndex()
    {
        return $this->setor->index();
    }

    public function store(SetorRequest $request)
    {
        Setor::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
        ]);
    }

    public function update(Setor $setor, SetorRequest $request)
    {
        $setor->update([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
        ]);
    }

    public function destroy(int $setor_id)
    {
        return $this->setor->findOrFail($setor_id)->delete();
    }

    public function ativar(int $setor_id)
    {
        return $this->setor->withTrashed()->where('id', $setor_id)->update(['deleted_at' => null]);
    }
}

