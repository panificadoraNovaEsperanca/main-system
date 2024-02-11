@extends('layouts.app')
@section('title', isset($fornecedor) ? "Editar fornecedor: $fornecedor->nome" : 'Cadastrar fornecedor')


@section('content')
    <form enctype="multipart/form-data"
        action="{{ isset($fornecedor) ? route('fornecedor.update', $fornecedor->id) : route('fornecedor.store') }}"
        method="POST">
        @csrf
        @if (isset($fornecedor))
            @method('PUT')
        @endif
            <div class="form-group">
                <label for="exampleInputEmail1" class="form-label">Nome</label>
                <input value="{{ isset($fornecedor) ? $fornecedor->nome : (old('nome') ?? '') }}" class="form-control" name="nome"
                    id="nome">
                @error('nome')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1" class="form-label">CNPJ</label>
                <input value="{{ isset($fornecedor) ? $fornecedor->cnpj : (old('cnpj') ?? '') }}" class="form-control" name="cnpj"
                    id="cnpj">
                @error('cnpj')
                    <span class="mt-1 text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Salvar</button>


    </form>


    <script>
        $(document).ready(function ($) {
      $('input[name=cnpj]').mask('99.999.999/9999-99')
    })
    </script>
@endsection

@push('scripts')

@endpush