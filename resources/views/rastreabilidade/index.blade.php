@extends('layouts.app')
@section('title', 'Rastreabilidade - Registro Diário')

@section('content')

    <div class="card mb-3">
        <div class="card-header bg-primary">
            <h3 class="card-title text-white">
                <i class="fas fa-clipboard-check"></i> Registro de Rastreabilidade
            </h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('rastreabilidade.index') }}" id="formSelecionarData" class="mb-3">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label for="data"><strong>Selecionar Data:</strong></label>
                        <input type="date" 
                            name="data" 
                            id="data" 
                            class="form-control" 
                            value="{{ $dataSelecionada->format('Y-m-d') }}"
                            max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                            required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Carregar Data
                        </button>
                        <a href="{{ route('rastreabilidade.index') }}" class="btn btn-secondary">
                            <i class="fas fa-calendar-day"></i> Hoje
                        </a>
                        <a href="{{ route('rastreabilidade.index', ['data' => $dataSelecionada->copy()->subDay()->format('Y-m-d')]) }}" class="btn btn-info">
                            <i class="fas fa-arrow-left"></i> Dia Anterior
                        </a>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{ route('rastreabilidade.configuracao') }}" class="btn btn-secondary">
                            <i class="fas fa-cog"></i> Configurar Produtos
                        </a>
                        <a href="{{ route('rastreabilidade.relatorio.index') }}" class="btn btn-info">
                            <i class="fas fa-file-pdf"></i> Relatórios
                        </a>
                    </div>
                </div>
            </form>
            
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Data Selecionada:</strong> {{ $dataSelecionada->format('d/m/Y') }}</p>
                    <p><strong>Lote do Dia Seguinte:</strong> <span class="badge badge-info" style="font-size: 16px;">{{ $lote }}</span></p>
                </div>
            </div>

            @if (!$verificacaoDiaAnterior['completo'])
                <div class="alert alert-danger mt-3">
                    <h5><i class="fas fa-exclamation-triangle"></i> Atenção!</h5>
                    <p><strong>Não é possível registrar o dia atual.</strong></p>
                    <p>É necessário completar o registro do dia anterior (<strong>{{ $verificacaoDiaAnterior['data_anterior'] }}</strong>) primeiro.</p>
                    <p>Produtos que ainda não foram registrados no dia anterior:</p>
                    <ul>
                        @foreach ($verificacaoDiaAnterior['produtos_faltando'] as $produtoRastreavelId)
                            @php
                                $produtoRastreavel = $produtosRastreaveis->firstWhere('id', $produtoRastreavelId);
                            @endphp
                            @if ($produtoRastreavel)
                                <li>{{ $produtoRastreavel->produto->nome ?? 'Produto ID: ' . $produtoRastreavelId }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    @if ($verificacaoDiaAnterior['completo'])
        <div class="accordion" id="accordionProdutos">
            @foreach ($produtosRastreaveis as $index => $produtoRastreavel)
                @php
                    $loteExistente = $lotesDataSelecionada->get($produtoRastreavel->id);
                    $accordionId = 'produto' . $produtoRastreavel->id;
                    $hasData = $loteExistente && (
                        $loteExistente->lote || 
                        $loteExistente->responsavel ||
                        $loteExistente->ingredientes->count() > 0
                    );
                @endphp
                <div class="card mb-2 produto-rastreavel-card" data-produto-id="{{ $produtoRastreavel->id }}">
                    <div class="card-header" id="heading{{ $accordionId }}" style="padding: 0.75rem 1rem;">
                        <h4 class="mb-0">
                            <button class="btn btn-link btn-block text-left p-0" 
                                type="button" 
                                data-toggle="collapse" 
                                data-target="#collapse{{ $accordionId }}" 
                                aria-expanded="{{ $hasData ? 'true' : 'false' }}" 
                                aria-controls="collapse{{ $accordionId }}"
                                style="text-decoration: none; color: inherit; font-size: 1.1rem;">
                                <i class="fas fa-chevron-{{ $hasData ? 'down' : 'right' }} mr-2"></i>
                                <i class="fas fa-box mr-2"></i> {{ $produtoRastreavel->produto->nome }}
                                @if ($loteExistente)
                                    <span class="badge badge-success float-right">Já Registrado</span>
                                @elseif ($hasData)
                                    <span class="badge badge-info float-right">Em Preenchimento</span>
                                @endif
                            </button>
                        </h4>
                    </div>
                    <div id="collapse{{ $accordionId }}" 
                        class="collapse {{ $hasData ? 'show' : '' }}" 
                        aria-labelledby="heading{{ $accordionId }}" 
                        data-parent="#accordionProdutos">
                        <div class="card-body">
                    <form class="form-rastreabilidade" 
                        action="{{ route('rastreabilidade.store') }}" 
                        method="POST"
                        data-produto-id="{{ $produtoRastreavel->id }}">
                        @csrf

                        <input type="hidden" name="produto_rastreavel_id" value="{{ $produtoRastreavel->id }}">
                        <input type="hidden" name="data_producao" value="{{ $dataSelecionada->format('Y-m-d') }}">

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Número do Lote <span class="text-danger">*</span></label>
                                <input type="text" 
                                    class="form-control campo-lote" 
                                    name="lote" 
                                    value="{{ $loteExistente ? $loteExistente->lote : $lote }}"
                                    maxlength="4"
                                    required
                                    readonly
                                    style="background-color: #e9ecef;">
                            </div>
                            <div class="col-md-4">
                                <label>Responsável</label>
                                <input type="text" 
                                    class="form-control" 
                                    name="responsavel" 
                                    value="{{ $loteExistente ? $loteExistente->responsavel : Auth::user()->name }}"
                                    placeholder="Nome do responsável"
                                    readonly
                                    style="background-color: #e9ecef;">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="form-check">
                                    <input type="checkbox" 
                                        class="form-check-input checkbox-nao-produzido" 
                                        name="nao_produzido" 
                                        value="1"
                                        {{ $loteExistente && $loteExistente->nao_produzido ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        Não produzido no dia
                                    </label>
                                </div>
                            </div>
                        </div>

                        @if (!$loteExistente || !$loteExistente->nao_produzido)
                            <div class="secao-ingredientes mt-4">
                                <h5 class="mb-4 pb-2 border-bottom">
                                    <i class="fas fa-list"></i> Ingredientes
                                    <small class="text-muted">({{ $produtoRastreavel->insumos->count() }} ingrediente(s))</small>
                                </h5>
                                
                                @foreach ($produtoRastreavel->insumos as $indexIng => $produtoInsumo)
                                    @php
                                        $ingredienteExistente = $loteExistente 
                                            ? $loteExistente->ingredientes->firstWhere('insumo_id', $produtoInsumo->insumo_id)
                                            : null;
                                    @endphp
                                    <div class="mb-4 ingrediente-card" data-insumo-id="{{ $produtoInsumo->insumo_id }}">
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <h6 class="mb-0 pb-2 border-bottom">
                                                    <strong><i class="fas fa-flask"></i> {{ $produtoInsumo->insumo->nome }}</strong>
                                                </h6>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label class="font-weight-bold mb-1">Marca</label>
                                                <input type="text" 
                                                    class="form-control campo-ingrediente" 
                                                    name="ingredientes[{{ $indexIng }}][marca]"
                                                    value="{{ $ingredienteExistente ? $ingredienteExistente->marca : '' }}"
                                                    placeholder="Digite a marca"
                                                    data-field="marca">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="font-weight-bold mb-1">Lote do Ingrediente</label>
                                                <input type="text" 
                                                    class="form-control campo-ingrediente" 
                                                    name="ingredientes[{{ $indexIng }}][lote_ingrediente]"
                                                    value="{{ $ingredienteExistente ? $ingredienteExistente->lote_ingrediente : '' }}"
                                                    placeholder="Digite o lote"
                                                    data-field="lote_ingrediente">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="font-weight-bold mb-1">Característica Sensorial</label>
                                                <input type="text" 
                                                    class="form-control campo-ingrediente" 
                                                    name="ingredientes[{{ $indexIng }}][caracteristica_sensorial]"
                                                    value="{{ $ingredienteExistente ? $ingredienteExistente->caracteristica_sensorial : 'Conforme' }}"
                                                    placeholder="Conforme"
                                                    data-field="caracteristica_sensorial">
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="font-weight-bold mb-1">Validade Original</label>
                                                <input type="date" 
                                                    class="form-control campo-ingrediente" 
                                                    name="ingredientes[{{ $indexIng }}][validade_original]"
                                                    value="{{ $ingredienteExistente && $ingredienteExistente->validade_original ? $ingredienteExistente->validade_original->format('Y-m-d') : '' }}"
                                                    data-field="validade_original">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="font-weight-bold mb-1">Data de Abertura</label>
                                                <input type="date" 
                                                    class="form-control campo-data-abertura" 
                                                    name="ingredientes[{{ $indexIng }}][data_abertura]"
                                                    value="{{ $ingredienteExistente && $ingredienteExistente->data_abertura ? $ingredienteExistente->data_abertura->format('Y-m-d') : '' }}"
                                                    data-field="data_abertura">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="font-weight-bold mb-1">Validade Após Aberto</label>
                                                <input type="date" 
                                                    class="form-control campo-validade-apos-aberto" 
                                                    name="ingredientes[{{ $indexIng }}][validade_apos_aberto]"
                                                    value="{{ $ingredienteExistente && $ingredienteExistente->validade_apos_aberto ? $ingredienteExistente->validade_apos_aberto->format('Y-m-d') : '' }}"
                                                    readonly
                                                    style="background-color: #e9ecef;"
                                                    data-field="validade_apos_aberto">
                                            </div>
                                        </div>
                                        
                                        <input type="hidden" name="ingredientes[{{ $indexIng }}][insumo_id]" value="{{ $produtoInsumo->insumo_id }}">
                                    </div>
                                @endforeach
                            </div>
                        @endif

                            <button type="submit" class="btn btn-primary btn-salvar-produto">
                                <i class="fas fa-save"></i> {{ $loteExistente ? 'Atualizar' : 'Salvar' }}
                            </button>
                        </form>
                        
                        @if ($loteExistente)
                            <div class="alert alert-info mt-3">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <div>
                                        <i class="fas fa-info-circle"></i> 
                                        <strong>Registro existente.</strong>
                                        @if ($loteExistente->ultima_edicao_em)
                                            <br>
                                            <small>
                                                Última edição: {{ $loteExistente->ultima_edicao_em->format('d/m/Y H:i') }}
                                                @if ($loteExistente->ultimaEdicaoPor)
                                                    por {{ $loteExistente->ultimaEdicaoPor->name }}
                                                @endif
                                            </small>
                                        @else
                                            <br>
                                            <small>
                                                Registro criado em: {{ $loteExistente->created_at->format('d/m/Y H:i') }}
                                                @if ($loteExistente->user)
                                                    por {{ $loteExistente->user->name }}
                                                @endif
                                            </small>
                                        @endif
                                    </div>
                                    <form action="{{ route('rastreabilidade.destroy', $loteExistente) }}" 
                                          method="POST" 
                                          class="d-inline mt-2"
                                          onsubmit="return confirm('Tem certeza que deseja excluir este registro? Esta ação não pode ser desfeita.');">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="data" value="{{ $dataSelecionada->format('Y-m-d') }}">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Excluir
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Calcular validade após aberto quando data de abertura é preenchida
    $('.campo-data-abertura').on('change', function() {
        const dataAbertura = $(this).val();
        const campoValidade = $(this).closest('.ingrediente-card').find('.campo-validade-apos-aberto');
        
        if (dataAbertura) {
            const data = new Date(dataAbertura);
            data.setDate(data.getDate() + 30); // Adicionar 30 dias
            const dataFormatada = data.toISOString().split('T')[0];
            campoValidade.val(dataFormatada);
        } else {
            campoValidade.val('');
        }
    });

    // Desabilitar campos de ingredientes quando "Não produzido" está marcado
    $('.checkbox-nao-produzido').on('change', function() {
        const card = $(this).closest('.produto-rastreavel-card');
        const secaoIngredientes = card.find('.secao-ingredientes');
        const camposIngredientes = card.find('.campo-ingrediente, .campo-data-abertura, .campo-validade-apos-aberto');
        
        if ($(this).is(':checked')) {
            secaoIngredientes.slideUp();
            camposIngredientes.prop('disabled', true);
        } else {
            secaoIngredientes.slideDown();
            camposIngredientes.prop('disabled', false);
        }
    });

    // Atualizar badge e ícone do accordion do produto quando campos são preenchidos
    function atualizarEstadoAccordionProduto(card) {
        const campos = card.find('.campo-lote, .campo-ingrediente, .campo-data-abertura');
        let temDados = false;
        
        campos.each(function() {
            if ($(this).val() && $(this).val().trim() !== '') {
                temDados = true;
                return false; // break
            }
        });
        
        const button = card.find('.btn-link');
        const badge = button.find('.badge');
        const icon = button.find('i.fa-chevron-right, i.fa-chevron-down');
        
        if (temDados && !badge.hasClass('badge-success')) {
            // Se já tem badge "Já Registrado", não adicionar outro
            if (badge.length === 0 || !badge.hasClass('badge-success')) {
                // Remover badge "Em Preenchimento" se existir
                button.find('.badge-info').remove();
                if (badge.length === 0) {
                    button.append('<span class="badge badge-info float-right">Em Preenchimento</span>');
                }
            }
            icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
        } else if (!temDados) {
            button.find('.badge-info').remove();
            icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
        }
    }

    // Monitorar mudanças nos campos do produto
    $(document).on('input change', '.campo-lote, .campo-ingrediente, .campo-data-abertura', function() {
        const card = $(this).closest('.produto-rastreavel-card');
        atualizarEstadoAccordionProduto(card);
    });

    // Aplicar estado inicial
    $('.checkbox-nao-produzido:checked').trigger('change');
    
    // Atualizar estado inicial dos accordions de produtos
    $('.produto-rastreavel-card').each(function() {
        atualizarEstadoAccordionProduto($(this));
    });

    // Calcular validades já existentes
    $('.campo-data-abertura').each(function() {
        if ($(this).val()) {
            $(this).trigger('change');
        }
    });

    // Validação antes de salvar
    $('.form-rastreabilidade').on('submit', function(e) {
        const checkboxNaoProduzido = $(this).find('.checkbox-nao-produzido');
        
        if (!checkboxNaoProduzido.is(':checked')) {
            // Verificar se pelo menos um campo de ingrediente foi preenchido
            const temIngredientePreenchido = $(this).find('.campo-ingrediente').filter(function() {
                return $(this).val() && $(this).val().trim() !== '';
            }).length > 0;
            
            // Não é obrigatório preencher, mas se preencher um, recomenda-se preencher os demais
            // Por enquanto, apenas validamos o lote
        }

        const lote = $(this).find('.campo-lote').val();
        if (!lote || lote.length !== 4) {
            e.preventDefault();
            alert('O número do lote deve ter exatamente 4 caracteres (formato MMDD).');
            return false;
        }
    });

    // Máscara para lote (apenas números, máximo 4 dígitos)
    $('.campo-lote').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').substring(0, 4);
    });
});
</script>
@endpush
