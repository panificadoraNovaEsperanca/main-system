@extends('layouts.app')
@section('title', (isset($marca) ? "Editar marca: $marca->nome" :'Cadastrar marca'))


@section('content')
<form enctype="multipart/form-data" action="{{ isset($marca) ? route('marca.update',$marca->id) : route('marca.store') }}"
        method="POST">
        @csrf
        @if(isset($marca))
            @method('PUT')
        @endif
        <div class="row">
            <div class="col-12">
                <label for="exampleInputEmail1" class="form-label">Nome</label>
                <input value="{{isset($marca) ? $marca->nome : ''}}" class="form-control" name="nome" id="nome">
                @error('nome')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
        </div>
 

        <button type="submit" class="btn btn-primary mt-3">Salvar</button>

    </form>



@endsection

