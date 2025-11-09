<?php

namespace App\Services;

use App\Http\Requests\SetorRequest;
use App\Models\Setor;
use App\Repositories\SetorRepository;

class SetorService
{
    public static function store(SetorRequest $request, SetorRepository $setorRepository)
    {
        $setorRepository->store($request);
    }

    public static function update(SetorRequest $request, int $setor_id, SetorRepository $setorRepository)
    {
        $setor = Setor::findOrFail($setor_id);
        $setorRepository->update($setor, $request);
    }
}

