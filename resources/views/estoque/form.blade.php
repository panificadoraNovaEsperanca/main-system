@extends('layouts.app')
@section('title', isset($estoque) ? "Editar estoque: $estoque->nome" : 'Cadastrar estoque')


@section('content')

    <form enctype="multipart/form-data"
        action="{{ isset($estoque) ? route('estoque.update', $estoque->id) : route('estoque.store') }}" method="POST">
        @csrf
        @if (isset($estoque))
            @method('PUT')
        @endif
        <div class="row mb-3">
            <div class="col-4">
                <label for="exampleInputEmail1" class="form-label">Tipo</label>

                <select class="custom-select select2" name="tipo">
                    <option selected disabled>Selecione uma opção</option>
                    <option value="entrada">Entrada</option>
                    <option value="saida">Saída</option>
                </select>
                  @error('tipo')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
            <div class="col-4">
                <label for="exampleInputEmail1" class="form-label">Insumo</label>

                <select class="custom-select select2" name="insumo_id">
                    <option selected disabled>Selecione uma opção</option>
                    @foreach ($insumos as $insumo)
                        <option value="{{ $insumo->id }}">{{ $insumo->nome }}</option>
                    @endforeach
                </select>
                @error('insumo_id')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
            <div class="col-4">
                <label for="exampleInputEmail1" class="form-label">Valor da transação</label>
                <input name="valor" type="number" step="0.0001" min="0"
                    value="{{ isset($estoque) ? $estoque->valor : old('valor') ?? '' }}" class="form-control"
                    id="valor">
                @error('valor')
                    <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <textarea rows="3" type="text" class="observacao form-control " name="descricao"></textarea>
            </div>
        </div>

        <small class="text-danger">ATENÇÃO, APÓS INSERIR NÃO SERÁ POSSÍVEL EXCLUIR NEM EDITAR ESTE REGISTRO.</small>
        <br>
        <button type="submit" class="btn btn-primary mt-4">Salvar</button>

    </form>

    <script>
        $('.select2').select2({
            width: '100%'
        })
    </script>
@endsection
