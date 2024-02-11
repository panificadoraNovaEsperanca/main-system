@extends('layouts.app')
@section('title', isset($produto) ? "Editar produto: $produto->nome" : 'Cadastrar produto')


@section('content')

    <form enctype="multipart/form-data"
        action="{{ isset($produto) ? route('produto.update', $produto->id) : route('produto.store') }}" method="POST">
        @csrf
        @if (isset($produto))
            @method('PUT')
        @endif
        <div class="row">
            <div class="col-12">
                <label for="exampleInputEmail1" class="form-label">Nome</label>
                <input name="nome" value="{{ isset($produto) ? $produto->nome : old('nome') ?? '' }}" class="form-control"
                    id="codigoProduto">
                @error('nome')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
            <div class="col-3">
                <label for="exampleInputEmail1" class="form-label">Unidade de Medida</label>
                <input name="unidade"
                    value="{{ isset($produto) ? $produto->unidade : old('unidade') ?? '' }}"
                    class="form-control" id="unidadeMedida">
                @error('unidade')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
            <div class="col-3">
                <label for="exampleInputEmail1" class="form-label">Preço A</label>
                <input 
                    value="{{ isset($produto) ? $produto->precos['a'] :  '' }}"
                    class="form-control" name="precoA">
                @error('precoA')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
            <div class="col-3">
                <label for="exampleInputEmail1" class="form-label">Preço B</label>
                <input
                    value="{{ isset($produto) ? $produto->precos['b'] :  '' }}"
                    class="form-control" name="precoB">
                @error('precoB')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
            <div class="col-3">
                <label for="exampleInputEmail1" class="form-label">Preço C</label>
                <input 
                    value="{{ isset($produto) ? $produto->precos['c'] : '' }}"
                    class="form-control" name="precoC">
                @error('precoC')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>





        </div>
        <button type="submit" class="btn btn-primary mt-4">Salvar</button>

    </form>



@endsection
{{-- <div class="form-floating">
                            <label for="floatingTextarea2">Informações Nutricionais</label>
                            <textarea name="informacaoNutricional" value="" class="form-control" id="informacaoNutricional"
                                style="height: 100px;resize:none">{{ isset($produto) ? $produto->informacao_nutricional : old('informacaoNutricional') ?? '' }}</textarea>
                            @error('informacaoNutricional')
                                <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                            @enderror
                        </div> --}}
