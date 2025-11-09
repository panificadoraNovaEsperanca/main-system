@extends('layouts.app')
@section('title', isset($produto) ? "Editar produto: $produto->nome" : 'Cadastrar produto')


@section('content')

    <form enctype="multipart/form-data"
        action="{{ isset($produto) ? route('produto.update', $produto->id) : route('produto.store') }}" method="POST">
        @csrf
        @if (isset($produto))
            @method('PUT')
        @endif
        <div class="row mb-3">
            <div class="col-4">
                <label for="exampleInputEmail1" class="form-label">Nome</label>
                <input name="nome" value="{{ isset($produto) ? $produto->nome : old('nome') ?? '' }}" class="form-control"
                    id="codigoProduto">
                @error('nome')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
            <div class="col-4">
                <label for="exampleInputEmail1" class="form-label">Unidade de Medida</label>
                <input name="unidade" value="{{ isset($produto) ? $produto->unidade : old('unidade') ?? '' }}"
                    class="form-control" id="unidadeMedida">
                @error('unidade')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
            <div class="col-4">
                <label for="exampleInputEmail1" class="form-label">Categoria</label>
                <select class="custom-select select2" name="categoria_id">
                    <option hidden disabled {{$produto->categoria_id == null ? 'selected' : ''}}>Selecione uma opção</option>
                    @foreach ($categorias as $categoria)
                        <option {{ isset($produto) && $categoria->id == $produto->categoria_id ? 'selected' : '' }}
                            value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-2">
                <label for="exampleInputEmail1" class="form-label">Preço A</label>
                <input value="{{ isset($produto) && isset($produto->precos['a']) ? $produto->precos['a'] : '' }}"
                    class="form-control" name="precoA">
                @error('precoA')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
            <div class="col-2">
                <label for="exampleInputEmail1" class="form-label">Preço B</label>
                <input value="{{ isset($produto) && isset($produto->precos['b']) ? $produto->precos['b'] : '' }}"
                    class="form-control" name="precoB">
                @error('precoB')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
            <div class="col-2">
                <label for="exampleInputEmail1" class="form-label">Preço C</label>
                <input value="{{ isset($produto) && isset($produto->precos['c']) ? $produto->precos['c'] : '' }}"
                    class="form-control" name="precoC">
                @error('precoC')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
            <div class="col-2">
                <label for="exampleInputEmail1" class="form-label">Preço D</label>
                <input value="{{ isset($produto) && isset($produto->precos['d']) ? $produto->precos['d'] : '' }}"
                    class="form-control" name="precoD">
                @error('precoD')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
            <div class="col-2">
                <label for="exampleInputEmail1" class="form-label">Preço E</label>
                <input value="{{ isset($produto) && isset($produto->precos['e']) ? $produto->precos['e'] : '' }}"
                    class="form-control" name="precoE">
                @error('precoE')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
            <div class="col-2">
                <label for="exampleInputEmail1" class="form-label">Preço F</label>
                <input value="{{ isset($produto) && isset($produto->precos['f']) ? $produto->precos['f'] : '' }}"
                    class="form-control" name="precoF">
                @error('precoF')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-2">
                <label for="exampleInputEmail1" class="form-label">Preço G</label>
                <input value="{{ isset($produto) && isset($produto->precos['g']) ? $produto->precos['g'] : '' }}"
                    class="form-control" name="precoG">
                @error('precoG')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
            <div class="col-2">
                <label for="exampleInputEmail1" class="form-label">Setor de produção 1</label>
                <input value="{{ isset($produto) && isset($produto->setor) ? $produto->setor : '' }}" class="form-control"
                    name="setor">
            </div>
              <div class="col-2">
                <label for="exampleInputEmail1" class="form-label">Setor de produção 2</label>
                <input value="{{ isset($produto) && isset($produto->setor_1) ? $produto->setor_1 : '' }}" class="form-control"
                    name="setor_1">
            </div>
              <div class="col-2">
                <label for="exampleInputEmail1" class="form-label">Setor de produção 3</label>
                <input value="{{ isset($produto) && isset($produto->setor_2) ? $produto->setor_2 : '' }}" class="form-control"
                    name="setor_2">
            </div>
              <div class="col-2">
                <label for="exampleInputEmail1" class="form-label">Setor de produção 4</label>
                <input value="{{ isset($produto) && isset($produto->setor_3) ? $produto->setor_3 : '' }}" class="form-control"
                    name="setor_3">
            </div>
            <div class="col-2">
                <label for="exampleInputEmail1" class="form-label">Quantidade por embalagem</label>
                <input
                    value="{{ isset($produto) && isset($produto->quantidade_embalagem) ? $produto->quantidade_embalagem : '' }}"
                    class="form-control" name="quantidade_embalagem">
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Salvar</button>

    </form>

    <script>
        $('.select2').select2({
            width: '100%'
        })
    </script>
@endsection
