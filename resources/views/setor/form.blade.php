@extends('layouts.app')
@section('title', isset($setor) ? "Editar setor $setor->nome" : 'Cadastrar setor')


@section('content')
    <form enctype="multipart/form-data"
        action="{{ isset($setor) ? route('setor.update', $setor->id) : route('setor.store') }}"
        method="POST">
        @csrf
        @if (isset($setor))
            @method('PUT')
        @endif
        <div class="row">
            <div class="col-12">
                <label for="exampleInputEmail1" class="form-label">Nome</label>
                <input value="{{ isset($setor) ? $setor->nome : old('nome') ?? '' }}" class="form-control" name="nome"
                    id="nome">
                @error('nome')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>

        </div>
        <div class="row">
            <div class="col-12 mt-1">
                <div class="form-floating">
                    <label for="floatingTextarea2">Descrição</label>
                    <textarea name="descricao" class="form-control" id="descricaoSetor" style="height: 100px;resize:none">{{ isset($setor) ? $setor->descricao : old('descricao') ?? '' }}</textarea>
                    @error('descricao')
                        <span class="mt-1 text-red p-1 rounded"><small>{{ $message }}</small></span>
                    @enderror
                </div>
            </div>
        </div>


        <button type="submit" class="btn btn-primary mt-3">Salvar</button>

    </form>

@endsection

