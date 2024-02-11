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
            <div class="col-6">
                <div class="row">
                    <div class="col-12">
                        <label for="exampleInputEmail1" class="form-label">Nome</label>
                        <input name="nome" value="{{ isset($produto) ? $produto->nome : old('nome') ?? '' }}"
                            class="form-control" id="codigoProduto">
                        @error('nome')
                            <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label for="exampleInputEmail1" class="form-label">Unidade de Medida</label>
                        <input name="unidade_medida"
                            value="{{ isset($produto) ? $produto->unidade_medida : old('unidade_medida') ?? '' }}"
                            class="form-control" id="unidadeMedida">
                        @error('unidade_medida')
                            <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                        @enderror
                    </div>

                    <div class="col-6 ">
                        <label for="exampleInputEmail1" class="form-label">Categoria</label>
                        <div class="input-group  ">
                            <select name="categoria"
                                value="{{ isset($produto) ? $produto->categoria_id : old('categoria') ?? '' }}"
                                class="custom-select" id="inputGroupSelect01">
                                <option value="-1" selected>Selecione uma opção
                                <option>
                                    @foreach ($categorias as $categoria)
                                <option
                                    {{ isset($produto) ? ($produto->categoria_id == $categoria->id ? 'selected' : '') : (old('categoria') == $categoria->id ? 'selected' : '') }}
                                    value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('categoria')
                            <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                        @enderror
                    </div>
                    <div class="col-6 ">
                        <label for="exampleInputEmail1" class="form-label">Marca</label>
                        <div class="input-group  ">

                            <select name="marca" value="{{ isset($produto) ? $produto->nome : old('marca') }}"
                                class="custom-select" id="inputGroupSelect01">
                                <option value="-1" selected>Selecione uma opção
                                <option>
                                    @foreach ($marcas as $marca)
                                <option
                                    {{ isset($produto) ? ($produto->marca_id == $marca->id ? 'selected' : '') : (old('marca') == $marca->id ? 'selected' : '') }}
                                    value="{{ $marca->id }}">{{ $marca->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('marca')
                            <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                        @enderror
                    </div>
                    <div class="col-6 ">
                        <label for="exampleInputEmail1" class="form-label">Fornecedores</label>
                        <div class="input-group  ">

                            <select name="fornecedor"
                                value="{{ isset($produto) ? $produto->nome : old('fornecedor') ?? '' }}"
                                class="custom-select" id="inputGroupSelect01">
                                <option value="-1" selected>Selecione uma opção
                                <option>
                                    @foreach ($fornecedores as $fornecedor)
                                <option
                                    {{ isset($produto) ? ($produto->fornecedor_id == $fornecedor->id ? 'selected' : '') : (old('fornecedor') == $fornecedor->id ? 'selected' : '') }}
                                    value="{{ $fornecedor->id }}">{{ $fornecedor->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('fornecedor')
                            <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="exampleInputEmail1" class="form-label">Responsável</label>
                        <input name="responsavel" class="form-control" id="responsavel" disabled
                            value="{{ Auth::user()->name }}">
                        @error('responsavel')
                            <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="row">
                    <div class="col-12 ">
                        <div class="form-floating">
                            <label for="floatingTextarea2">Descrição</label>
                            <textarea name="descricao" value="" class="form-control" id="descricao" style="height: 100px;resize:none">{{ isset($produto) ? $produto->descricao : old('descricao') ?? '' }}</textarea>
                            @error('descricao')
                                <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                            @enderror
                        </div>
                    </div>

                </div>
                
                <h5  class=" mt-3">Informações Nutricionais</h5>
                <div class=" mt-1 p-2 rounded shadow" style="background-color:#F4F6F9;">
                    <div class="row">
                        <div class="col-2">
                            <label for="exampleInputEmail1" class="form-label">Porção</label>
                            <input type="number" name="porcao" value="{{isset($produto) ? $produto->informacao_nutricional['porcao']: (old('porcao') ?? '')}}" class="form-control">
                            @error('porcao')
                                <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                            @enderror
                        </div>

                        <div class="col-2">
                            <label for="exampleInputEmail1" class="form-label">Proteína (g)</label>
                            <input type="number" name="proteina" value="{{isset($produto) ? $produto->informacao_nutricional['proteina']: (old('proteina') ?? '')}}" class="form-control">
                            @error('proteina')
                                <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                            @enderror
                        </div>
                        <div class="col-3">
                            <label for="exampleInputEmail1" class="form-label">Carboidratos (g)</label>
                            <input type="number" name="carboidrato" value="{{isset($produto) ? $produto->informacao_nutricional['carboidrato']: (old('carboidrato') ?? '')}}" class="form-control">
                            @error('carboidrato')
                                <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                            @enderror
                        </div>
                        <div class="col-3">
                            <label for="exampleInputEmail1" class="form-label">Gorduras Totais (g)</label>
                            <input type="number" name="gordura_total" value="{{isset($produto) ? $produto->informacao_nutricional['gordura_total']: (old('gordura_total') ?? '')}}" class="form-control">
                            @error('gordura_total')
                                <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                            @enderror
                        </div>

                    </div>
                </div>
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
