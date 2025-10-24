@extends('layouts.app')
@section('title', isset($insumo) ? "Editar insumo: $insumo->nome" : 'Cadastrar insumo')


@section('content')

    <form enctype="multipart/form-data"
        action="{{ isset($insumo) ? route('insumo.update', $insumo->id) : route('insumo.store') }}" method="POST">
        @csrf
        @if (isset($insumo))
            @method('PUT')
        @endif
        <div class="row mb-3">
            <div class="col-3">
                <label for="exampleInputEmail1" class="form-label">Nome</label>
                <input name="nome" value="{{ isset($insumo) ? $insumo->nome : old('nome') ?? '' }}" class="form-control"
                    id="codigoinsumo">
                @error('nome')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
            <div class="col-3">
                <label for="exampleInputEmail1" class="form-label">Unidade de Medida</label>
                <input name="unidade_medida"
                    value="{{ isset($insumo) ? $insumo->unidade_medida : old('unidade_medida') ?? '' }}"
                    class="form-control" id="unidade_medida">
                @error('unidade_medida')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
            <div class="col-3">
                <label for="exampleInputEmail1" class="form-label">Quantidade mínima</label>
                <input name="quantidade_minima"
                type="number" step="0.0001" min="0"
                    value="{{ isset($insumo) ? $insumo->quantidade_minima : old('quantidade_minima') ?? '' }}"
                    class="form-control" id="quantidade_minima">
                @error('quantidade_minima')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
            @if (isset($insumo))
                <div class="col-3">
                    <label for="exampleInputEmail1" class="form-label">Quantidade Atual</label>
                    <p>
                        {{ $insumo->quantidade_atual }}
                    </p>
                </div>
            @endif
        </div>


        <button type="submit" class="btn btn-primary mt-4">Salvar</button>

    </form>

    <script>
        $('.select2').select2({
            width: '100%'
        })
    </script>
@endsection
