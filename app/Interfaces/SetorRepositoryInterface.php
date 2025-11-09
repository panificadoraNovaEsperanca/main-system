<?php

namespace App\Interfaces;

use App\Http\Requests\SetorRequest;
use App\Models\Setor;

interface SetorRepositoryInterface
{
    public function getIndex();
    public function update(Setor $setor, SetorRequest $request);
    public function destroy(int $id);
    public function store(SetorRequest $request);
}

