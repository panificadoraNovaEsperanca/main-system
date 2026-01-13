@extends('layouts.app')
@section('title', isset($produto) ? "Editar produto: $produto->nome" : 'Cadastrar produto')

@push('styles')
    <style>
        .precos-section {
            background: linear-gradient(135deg, #3D2C1F 0%, #5a4430 100%);
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .precos-section h5 {
            color: white;
            margin-bottom: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .precos-section h5 i {
            font-size: 1.2em;
        }
        
        .preco-input-group {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .preco-input-group:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .preco-input-group label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            display: block;
        }
        
        .preco-input-group input {
            border: 2px solid #e9ecef;
            border-radius: 6px;
            padding: 10px;
            transition: border-color 0.3s;
        }
        
        .preco-input-group input:focus {
            border-color: #3D2C1F;
            box-shadow: 0 0 0 0.2rem rgba(61, 44, 31, 0.25);
        }
        
        .info-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #3D2C1F;
        }
        
        .info-section h6 {
            color: #495057;
            margin-bottom: 15px;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')

    <form enctype="multipart/form-data"
        action="{{ isset($produto) ? route('produto.update', $produto->id) : route('produto.store') }}" method="POST">
        @csrf
        @if (isset($produto))
            @method('PUT')
        @endif
        
        <!-- Informações Básicas -->
        <div class="info-section">
            <h6><i class="fas fa-info-circle mr-2"></i>Informações Básicas</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="nome" class="form-label">Nome do Produto <span class="text-danger">*</span></label>
                    <input name="nome" value="{{ isset($produto) ? $produto->nome : old('nome') ?? '' }}" 
                        class="form-control" id="nome" placeholder="Digite o nome do produto">
                    @error('nome')
                        <span class="mt-1 text-danger p-1 rounded"><small>{{ $message }}</small></span>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="unidade" class="form-label">Unidade de Medida <span class="text-danger">*</span></label>
                    <input name="unidade" value="{{ isset($produto) ? $produto->unidade : old('unidade') ?? '' }}"
                        class="form-control" id="unidade" placeholder="Ex: kg, un, lt">
                    @error('unidade')
                        <span class="mt-1 text-danger p-1 rounded"><small>{{ $message }}</small></span>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="categoria_id" class="form-label">Categoria</label>
                    <select class="custom-select select2" name="categoria_id" id="categoria_id">
                        <option value="">Selecione uma categoria</option>
                        @foreach ($categorias as $categoria)
                            <option {{ isset($produto) && $produto->categoria_id == $categoria->id ? 'selected' : '' }}
                                value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                        <span class="mt-1 text-danger p-1 rounded"><small>{{ $message }}</small></span>
                    @enderror
                </div>
                @hasGroup('administrador')
                <div class="col-md-2 mb-3">
                    <label for="setor_id" class="form-label">Setor</label>
                    <select class="custom-select select2" name="setor_id" id="setor_id">
                        <option value="">Selecione um setor</option>
                        @if(isset($setores) && $setores->count() > 0)
                            @foreach ($setores as $setor)
                                <option {{ (isset($produto) && $produto->setor_id && $produto->setor_id == $setor->id) || old('setor_id') == $setor->id ? 'selected' : '' }}
                                    value="{{ $setor->id }}">{{ $setor->nome }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('setor_id')
                        <span class="mt-1 text-danger p-1 rounded"><small>{{ $message }}</small></span>
                    @enderror
                </div>
                @endhasGroup
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="quantidade_embalagem" class="form-label">Quantidade por Embalagem</label>
                    <input name="quantidade_embalagem"
                        value="{{ isset($produto) && isset($produto->quantidade_embalagem) ? $produto->quantidade_embalagem : old('quantidade_embalagem') ?? '' }}"
                        class="form-control" id="quantidade_embalagem" type="number" step="0.01" placeholder="0.00">
                    @error('quantidade_embalagem')
                        <span class="mt-1 text-danger p-1 rounded"><small>{{ $message }}</small></span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Tabela de Preços - Destaque Visual -->
        <div class="precos-section">
            <h5>
                <i class="fas fa-dollar-sign"></i>
                Tabela de Preços
            </h5>
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="preco-input-group">
                        <label for="precoA">Preço A <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">R$</span>
                            </div>
                            <input type="text" class="form-control" name="precoA" id="precoA"
                                value="{{ isset($produto) && isset($produto->precos['a']) ? $produto->precos['a'] : old('precoA') ?? '' }}"
                                placeholder="0.00" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 44 || event.charCode == 46">
                        </div>
                        @error('precoA')
                            <span class="mt-1 text-danger"><small>{{ $message }}</small></span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="preco-input-group">
                        <label for="precoB">Preço B <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">R$</span>
                            </div>
                            <input type="text" class="form-control" name="precoB" id="precoB"
                                value="{{ isset($produto) && isset($produto->precos['b']) ? $produto->precos['b'] : old('precoB') ?? '' }}"
                                placeholder="0.00" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 44 || event.charCode == 46">
                        </div>
                        @error('precoB')
                            <span class="mt-1 text-danger"><small>{{ $message }}</small></span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="preco-input-group">
                        <label for="precoC">Preço C <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">R$</span>
                            </div>
                            <input type="text" class="form-control" name="precoC" id="precoC"
                                value="{{ isset($produto) && isset($produto->precos['c']) ? $produto->precos['c'] : old('precoC') ?? '' }}"
                                placeholder="0.00" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 44 || event.charCode == 46">
                        </div>
                        @error('precoC')
                            <span class="mt-1 text-danger"><small>{{ $message }}</small></span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="preco-input-group">
                        <label for="precoD">Preço D</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">R$</span>
                            </div>
                            <input type="text" class="form-control" name="precoD" id="precoD"
                                value="{{ isset($produto) && isset($produto->precos['d']) ? $produto->precos['d'] : old('precoD') ?? '' }}"
                                placeholder="0.00" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 44 || event.charCode == 46">
                        </div>
                        @error('precoD')
                            <span class="mt-1 text-danger"><small>{{ $message }}</small></span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="preco-input-group">
                        <label for="precoE">Preço E</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">R$</span>
                            </div>
                            <input type="text" class="form-control" name="precoE" id="precoE"
                                value="{{ isset($produto) && isset($produto->precos['e']) ? $produto->precos['e'] : old('precoE') ?? '' }}"
                                placeholder="0.00" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 44 || event.charCode == 46">
                        </div>
                        @error('precoE')
                            <span class="mt-1 text-danger"><small>{{ $message }}</small></span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="preco-input-group">
                        <label for="precoF">Preço F</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">R$</span>
                            </div>
                            <input type="text" class="form-control" name="precoF" id="precoF"
                                value="{{ isset($produto) && isset($produto->precos['f']) ? $produto->precos['f'] : old('precoF') ?? '' }}"
                                placeholder="0.00" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 44 || event.charCode == 46">
                        </div>
                        @error('precoF')
                            <span class="mt-1 text-danger"><small>{{ $message }}</small></span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="preco-input-group">
                        <label for="precoG">Preço G</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">R$</span>
                            </div>
                            <input type="text" class="form-control" name="precoG" id="precoG"
                                value="{{ isset($produto) && isset($produto->precos['g']) ? $produto->precos['g'] : old('precoG') ?? '' }}"
                                placeholder="0.00" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 44 || event.charCode == 46">
                        </div>
                        @error('precoG')
                            <span class="mt-1 text-danger"><small>{{ $message }}</small></span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="row mt-4 mb-4">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>Salvar Produto
                </button>
                <a href="{{ route('produto.index') }}" class="btn btn-secondary ml-2">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
            </div>
        </div>

    </form>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });

            // Máscara para valores monetários
            $('input[name^="preco"]').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                if (value.length > 0) {
                    value = (value / 100).toFixed(2) + '';
                    value = value.replace(".", ",");
                    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    $(this).val(value);
                }
            });
        });
    </script>
@endsection
