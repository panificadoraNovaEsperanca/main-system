@extends('layouts.app')
@section('title', isset($categoria) ? "Editar categoria $categoria->nome" : 'Cadastrar categoria')


@section('content')
    <form enctype="multipart/form-data"
        action="{{ isset($categoria) ? route('categoria.update', $categoria->id) : route('categoria.store') }}"
        method="POST">
        @csrf
        @if (isset($categoria))
            @method('PUT')
        @endif
        <div class="row">
            <div class="col-6">
                <label for="exampleInputEmail1" class="form-label">Nome</label>
                <input value="{{ isset($categoria) ? $categoria->nome : '' }}" class="form-control" name="nome"
                    id="nome">
                @error('nome')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
            <div class="col-6">
                <label for="exampleInputEmail1" class="form-label">Url da foto</label>
    
                <div class="custom-file">
                    <input name="url_capa" type="file" class="custom-file-input" id="imageCategoria"
                        aria-describedby="inputGroupFileAddon01">
                    <label class="custom-file-label" for="inputGroupFile01">Selecione o arquivo </label>
                </div>
                @error('url_capa')
                    <span class="mt-1 text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>

        </div>
        <div class="row">
            <div class="col-12 mt-1">
                <div class="form-floating">
                    <label for="floatingTextarea2">Descrição</label>
                    <textarea name="descricao" class="form-control" id="descricaoCategoria" style="height: 100px;resize:none">{{ isset($categoria) ? $categoria->descricao : '' }}</textarea>
                    @error('descricao')
                        <span class="mt-1 text-red p-1 rounded"><small>{{ $message }}</small></span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row my-2 ">

            <div class="col-4">
                <label for="floatingTextarea2">Imagem Atual</label>
                <div class="card p-2">
                    @if (isset($categoria))
                        <img id="previewImage" src="{{ $categoria->url_capa }}" class="card-img-top" alt=""
                            style="height: 200px;width:765px">
                    @else
                        <img id="previewImage" class="card-img-top" alt=""
                            style="height: 200px;width:765px">
                    @endif
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Salvar</button>

    </form>

    <script>
        document.getElementById('imageCategoria').addEventListener('change', function(event) {
            let output = document.getElementById('previewImage');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        })
    </script>


@endsection
