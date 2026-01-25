@extends('layouts.app')
@section('title', 'Configuração de Rastreabilidade')

@push('styles')
<style>
  #receitaModalOverlay {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    background-color: rgba(0, 0, 0, 0.5) !important;
    z-index: 99999 !important;
    display: none;
  }
  
  #receitaModalContent {
    position: fixed !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    background: white !important;
    border-radius: 4px !important;
    width: 90% !important;
    max-width: 800px !important;
    max-height: 90vh !important;
    overflow-y: auto !important;
    z-index: 100000 !important;
    display: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
  }
  
  #receitaModalContent * {
    transition: none !important;
    animation: none !important;
  }
  
  #receitaModalContent .btn:hover,
  #receitaModalContent .table tbody tr:hover {
    background-color: inherit !important;
    color: inherit !important;
  }
</style>
@endpush

@section('content')
    <!-- Busca e Paginação -->
    <form class="mr-2 d-flex row justify-content-between mb-3" id="formSearch" action="{{ route('rastreabilidade.configuracao') }}" method="GET">
        <div class="d-flex col-md-12 col-sm-12">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                </div>
                <input value="{{ request()->search ?? '' }}" type="text" id="search" name="search" class="form-control"
                    placeholder="Buscar produto..." aria-label="" aria-describedby="basic-addon1">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
                <a href="{{ route('rastreabilidade.configuracao') }}" class="btn btn-secondary ml-2">Limpar busca</a>
            </div>
        </div>

        <div class="d-flex row col-md-12 col-sm-12">
            <div class="input-group col-2">
                <select id="paginacao" name="paginacao" class="custom-select mr-2" style="min-width: 80px">
                    <option value="10" {{ (request()->paginacao ?? 20) == '10' ? 'selected' : '' }}>10</option>
                    <option value="20" {{ (request()->paginacao ?? 20) == '20' ? 'selected' : '' }}>20</option>
                    <option value="30" {{ (request()->paginacao ?? 20) == '30' ? 'selected' : '' }}>30</option>
                    <option value="50" {{ (request()->paginacao ?? 20) == '50' ? 'selected' : '' }}>50</option>
                </select>
            </div>
            {{ $produtos->appends(request()->query())->links() }}
        </div>
    </form>

    <form action="{{ route('rastreabilidade.storeConfiguracao') }}" method="POST" id="formConfiguracao">
        @csrf

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Configurar Produtos Rastreáveis</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">Configure a receita (ingredientes) de cada produto clicando em "Configurar Receita". A receita é salva automaticamente ao clicar em "Salvar Receita" no modal.</p>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="50">Rastreável</th>
                                <th>Nome do Produto</th>
                                <th>Ingredientes</th>
                                <th width="150">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produtos as $produto)
                                @php
                                    $produtoRastreavel = $produtosRastreaveis->get($produto->id);
                                    $isRastreavel = $produtoRastreavel && $produtoRastreavel->ativo;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" 
                                                class="form-check-input checkbox-rastreavel" 
                                                name="produtos[{{ $produto->id }}][ativo]"
                                                value="1"
                                                data-produto-id="{{ $produto->id }}"
                                                {{ $isRastreavel ? 'checked' : '' }}>
                                        </div>
                                        <input type="hidden" name="produtos[{{ $produto->id }}][produto_id]" value="{{ $produto->id }}">
                                    </td>
                                    <td>
                                        <strong>{{ $produto->nome }}</strong>
                                    </td>
                                    <td>
                                        @if ($produtoRastreavel && $produtoRastreavel->insumos->count() > 0)
                                            <span class="badge badge-success">
                                                {{ $produtoRastreavel->insumos->count() }} ingrediente(s) configurado(s)
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">Nenhum ingrediente</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" 
                                            class="btn btn-sm btn-primary btn-configurar-receita"
                                            data-produto-id="{{ $produto->id }}"
                                            data-produto-nome="{{ $produto->nome }}">
                                            <i class="fas fa-cog"></i> Configurar Receita
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($produtos->isEmpty())
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i> Nenhum produto encontrado.
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Atualizar Status dos Produtos
                </button>
                <a href="{{ route('rastreabilidade.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </form>

<div id="receitaModalOverlay"></div>
<div id="receitaModalContent">
  <div style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
      <h5 style="margin: 0;">
        Configurar Receita: <b><span id="modalProdutoNome"></span></b>
      </h5>
      <button type="button" id="closeReceitaModalBtn" style="background: none; border: none; font-size: 24px; cursor: pointer; padding: 0; width: 30px; height: 30px; line-height: 30px;">&times;</button>
    </div>
    <div>
      <input type="hidden" id="modalProdutoId" value="">
      <p class="text-muted mb-3">Selecione os ingredientes que compõem a receita deste produto e defina a ordem de exibição.</p>
      
      <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table table-sm table-bordered" style="margin-bottom: 0; border-collapse: collapse;">
          <thead style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 10;">
            <tr>
              <th width="50">Selecionar</th>
              <th >Nome do Insumo</th>
              <th width="100" >Ordem</th>
            </tr>
          </thead>
          <tbody id="tbodyInsumos">
            @foreach ($insumos as $insumo)
              <tr style="background-color: #fff;">
                <td>
                  <div class="form-check" style="margin: 0;">
                    <input type="checkbox" 
                      class="form-check-input checkbox-insumo" 
                      value="{{ $insumo->id }}"
                      data-insumo-id="{{ $insumo->id }}"
                      data-insumo-nome="{{ $insumo->nome }}"
                      style="cursor: pointer;">
                  </div>
                </td>
                <td style="padding: 8px;">{{ $insumo->nome }}</td>
                <td>
                  <input type="number" 
                    class="form-control form-control-sm ordem-insumo" 
                    value="0" 
                    min="0"
                    disabled
                    data-insumo-id="{{ $insumo->id }}"
                    style="width: 80px;">
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div style="margin-top: 20px; padding-top: 10px; border-top: 1px solid #ddd; text-align: right;">
      <button type="button" id="closeReceitaModalBtn2" class="btn btn-secondary">Cancelar</button>
      <button type="button" class="btn btn-primary" id="btnSalvarReceita">Salvar Receita</button>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    console.log('[RASTREABILIDADE] Inicializando configuração...');
    
    let receitasConfiguradas = {!! json_encode($produtosRastreaveis->mapWithKeys(function($pr) {
        // Usar string como chave para garantir compatibilidade com JavaScript
        $insumosArray = $pr->insumos->map(function($insumo) {
            return [
                'insumo_id' => (int)$insumo->insumo_id,
                'ordem' => (int)$insumo->ordem
            ];
        })->toArray();
        return [(string)$pr->produto_id => $insumosArray];
    })) !!};
    
    // Garantir que todas as chaves sejam strings
    let receitasConfiguradasNormalizadas = {};
    Object.keys(receitasConfiguradas).forEach(function(key) {
        receitasConfiguradasNormalizadas[String(key)] = receitasConfiguradas[key];
    });
    receitasConfiguradas = receitasConfiguradasNormalizadas;
    
    console.log('[RASTREABILIDADE] Receitas configuradas:', receitasConfiguradas);
    console.log('[RASTREABILIDADE] Tipo das chaves:', Object.keys(receitasConfiguradas).map(k => typeof k));
    console.log('[RASTREABILIDADE] Total de receitas carregadas:', Object.keys(receitasConfiguradas).length);
    
    // Atualizar badges na tabela ao carregar a página
    Object.keys(receitasConfiguradas).forEach(function(produtoId) {
        const receita = receitasConfiguradas[produtoId];
        if (receita && receita.length > 0) {
            const badge = $(`.btn-configurar-receita[data-produto-id="${produtoId}"]`)
                .closest('tr')
                .find('td:nth-child(3)');
            
            if (badge.length > 0) {
                badge.html(`<span class="badge badge-success">${receita.length} ingrediente(s) configurado(s)</span>`);
            }
        }
    });
    
    let processandoCheckbox = false;

    // Função para atualizar campo de ordem quando checkbox muda
    function atualizarOrdemCheckbox(e) {
        try {
            if (processandoCheckbox) {
                e.stopPropagation();
                return false;
            }
            
            e.stopPropagation();
            
            processandoCheckbox = true;
            const $checkbox = $(this);
            const insumoId = $checkbox.data('insumo-id');
            const ordemInput = $(`#receitaModalContent .ordem-insumo[data-insumo-id="${insumoId}"]`);
            
            console.log('[RASTREABILIDADE] Checkbox alterado - Insumo ID:', insumoId, 'Checked:', $checkbox.is(':checked'));
            
            if ($checkbox.is(':checked')) {
                ordemInput.prop('disabled', false);
                if (ordemInput.val() == 0 || ordemInput.val() == '0') {
                    const ordemAtual = $('#receitaModalContent .checkbox-insumo:checked').length;
                    ordemInput.val(ordemAtual);
                    console.log('[RASTREABILIDADE] Ordem definida automaticamente:', ordemAtual);
                }
            } else {
                ordemInput.prop('disabled', true).val(0);
                console.log('[RASTREABILIDADE] Campo ordem desabilitado');
            }
            
            setTimeout(function() {
                processandoCheckbox = false;
            }, 50);
            
            return true;
        } catch (error) {
            console.error('[RASTREABILIDADE] Erro ao atualizar ordem:', error);
            processandoCheckbox = false;
            return false;
        }
    }

    // Habilitar/desabilitar campo de ordem quando checkbox é marcado
    $(document).off('change.rastreabilidade', '#receitaModalContent .checkbox-insumo').on('change.rastreabilidade', '#receitaModalContent .checkbox-insumo', atualizarOrdemCheckbox);

    // Abrir modal de configuração de receita
    $(document).off('click.rastreabilidade', '.btn-configurar-receita').on('click.rastreabilidade', '.btn-configurar-receita', function(e) {
        try {
            console.log('[RASTREABILIDADE] Botão configurar receita clicado');
            e.preventDefault();
            e.stopPropagation();
            
            const produtoId = $(this).data('produto-id');
            const produtoNome = $(this).data('produto-nome');
            
            console.log('[RASTREABILIDADE] Produto ID:', produtoId, 'Nome:', produtoNome);
            
            if (!produtoId) {
                console.error('[RASTREABILIDADE] Erro: produtoId não encontrado');
                alert('Erro: Produto não identificado. Recarregue a página e tente novamente.');
                return;
            }
            
            $('#modalProdutoId').val(produtoId);
            $('#modalProdutoNome').text(produtoNome);
            
            // Limpar seleções anteriores (sem disparar eventos)
            processandoCheckbox = true;
            console.log('[RASTREABILIDADE] Limpando seleções anteriores...');
            
            $('#receitaModalContent .checkbox-insumo').off('change.rastreabilidade').each(function() {
                $(this).prop('checked', false);
            });
            $('#receitaModalContent .ordem-insumo').val(0).prop('disabled', true);
            
            // Reanexar eventos após limpar
            $(document).off('change.rastreabilidade', '#receitaModalContent .checkbox-insumo').on('change.rastreabilidade', '#receitaModalContent .checkbox-insumo', atualizarOrdemCheckbox);
            
            // Abrir modal primeiro
            openReceitaModal();
            
            // Aguardar um pouco para garantir que o modal está totalmente renderizado antes de preencher
            setTimeout(function() {
                // Converter produtoId para string e número para garantir compatibilidade
                const produtoIdStr = String(produtoId);
                const produtoIdNum = parseInt(produtoId);
                
                console.log('[RASTREABILIDADE] Procurando receita para produto ID (string):', produtoIdStr);
                console.log('[RASTREABILIDADE] Procurando receita para produto ID (número):', produtoIdNum);
                console.log('[RASTREABILIDADE] Todas as receitas configuradas:', receitasConfiguradas);
                console.log('[RASTREABILIDADE] Chaves disponíveis:', Object.keys(receitasConfiguradas));
                
                // Tentar encontrar a receita com diferentes formatos de chave
                let receita = receitasConfiguradas[produtoIdStr] || receitasConfiguradas[produtoIdNum] || receitasConfiguradas[produtoId];
                
                console.log('[RASTREABILIDADE] Receita encontrada para produto', produtoId, ':', receita);
                
                if (receita && receita.length > 0) {
                    console.log('[RASTREABILIDADE] Preenchendo modal - Receita:', receita);
                    receita.forEach(function(ingrediente, index) {
                        // Suportar tanto formato antigo (apenas ID) quanto novo (objeto com insumo_id e ordem)
                        const insumoId = typeof ingrediente === 'object' ? ingrediente.insumo_id : ingrediente;
                        const ordem = typeof ingrediente === 'object' && ingrediente.ordem ? ingrediente.ordem : (index + 1);
                        
                        console.log('[RASTREABILIDADE] Processando ingrediente - ID:', insumoId, 'Ordem:', ordem, 'Tipo:', typeof ingrediente);
                        
                        const checkbox = $(`#receitaModalContent .checkbox-insumo[data-insumo-id="${insumoId}"]`);
                        console.log('[RASTREABILIDADE] Checkbox encontrado:', checkbox.length > 0, 'Seletor:', `#receitaModalContent .checkbox-insumo[data-insumo-id="${insumoId}"]`);
                        
                        if (checkbox.length > 0) {
                            checkbox.off('change.rastreabilidade').prop('checked', true);
                            const ordemInput = $(`#receitaModalContent .ordem-insumo[data-insumo-id="${insumoId}"]`);
                            ordemInput.prop('disabled', false).val(ordem);
                            console.log('[RASTREABILIDADE] ✓ Preenchido - Insumo ID:', insumoId, 'Ordem:', ordem, 'Valor no input:', ordemInput.val());
                        } else {
                            console.warn('[RASTREABILIDADE] ✗ Checkbox não encontrado para insumo ID:', insumoId);
                            // Tentar encontrar todos os checkboxes para debug
                            console.log('[RASTREABILIDADE] Total de checkboxes no modal:', $('#receitaModalContent .checkbox-insumo').length);
                            $('#receitaModalContent .checkbox-insumo').each(function() {
                                console.log('[RASTREABILIDADE] Checkbox encontrado com data-insumo-id:', $(this).data('insumo-id'));
                            });
                        }
                    });
                    // Marcar checkbox automaticamente se já houver receita configurada
                    $(`.checkbox-rastreavel[data-produto-id="${produtoId}"]`).prop('checked', true);
                } else {
                    console.log('[RASTREABILIDADE] Nenhuma receita configurada para este produto');
                }
                
                processandoCheckbox = false;
            }, 300);
            
        } catch (error) {
            console.error('[RASTREABILIDADE] Erro ao abrir modal:', error);
            console.error('[RASTREABILIDADE] Stack trace:', error.stack);
            alert('Erro ao abrir modal. Verifique o console para mais detalhes.');
        }
    });
    
    // Função simples para abrir modal (padrão do pedido)
    function openReceitaModal() {
      document.getElementById('receitaModalOverlay').style.display = 'block';
      document.getElementById('receitaModalContent').style.display = 'block';
      document.body.style.overflow = 'hidden';
    }
    
    // Função simples para fechar modal (padrão do pedido)
    function closeReceitaModal() {
      document.getElementById('receitaModalOverlay').style.display = 'none';
      document.getElementById('receitaModalContent').style.display = 'none';
      document.body.style.overflow = '';
      processandoCheckbox = false;
      
      // Resetar botão de salvar ao fechar modal
      $('#btnSalvarReceita').prop('disabled', false).html('Salvar Receita');
    }
    
    // Event listeners para fechar modal (padrão do pedido)
    document.getElementById('closeReceitaModalBtn').addEventListener('click', closeReceitaModal);
    document.getElementById('closeReceitaModalBtn2').addEventListener('click', closeReceitaModal);
    document.getElementById('receitaModalOverlay').addEventListener('click', closeReceitaModal);
    
    // Fechar com ESC (padrão do pedido)
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        if (document.getElementById('receitaModalContent').style.display === 'block') {
          closeReceitaModal();
        }
      }
    });

    // Salvar receita no modal via AJAX
    $('#btnSalvarReceita').off('click.rastreabilidade').on('click.rastreabilidade', function() {
        const $btn = $(this);
        const produtoId = $('#modalProdutoId').val();
        
        if (!produtoId) {
            alert('Erro: Produto não identificado.');
            return;
        }
        
        // Validar se tem pelo menos um ingrediente selecionado
        const checkboxesMarcados = $('#receitaModalContent .checkbox-insumo:checked');
        if (checkboxesMarcados.length === 0) {
            alert('Selecione pelo menos um ingrediente para a receita.');
            return;
        }
        
        const insumosSelecionados = [];
        checkboxesMarcados.each(function() {
            const insumoId = $(this).data('insumo-id');
            const ordem = $(`#receitaModalContent .ordem-insumo[data-insumo-id="${insumoId}"]`).val() || 0;
            
            insumosSelecionados.push({
                insumo_id: parseInt(insumoId),
                ordem: parseInt(ordem) || 0
            });
        });

        // Ordenar por ordem
        insumosSelecionados.sort((a, b) => a.ordem - b.ordem);

        // Desabilitar botão durante requisição
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Salvando...');
        
        // Enviar via AJAX
        $.ajax({
            url: '{{ route("rastreabilidade.storeReceita") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                produto_id: produtoId,
                insumos: insumosSelecionados
            },
            success: function(response) {
                if (response.success) {
                    // Atualizar receitasConfiguradas na memória
                    receitasConfiguradas[String(produtoId)] = insumosSelecionados.map(i => ({
                        insumo_id: i.insumo_id,
                        ordem: i.ordem
                    }));
                    
                    // Marcar checkbox automaticamente
                    $(`.checkbox-rastreavel[data-produto-id="${produtoId}"]`).prop('checked', true);
                    
                    // Atualizar badge na tabela
                    const badge = $(`.btn-configurar-receita[data-produto-id="${produtoId}"]`)
                        .closest('tr')
                        .find('td:nth-child(3)');
                    
                    badge.html(`<span class="badge badge-success">${response.insumos_count} ingrediente(s) configurado(s)</span>`);
                    
                    // Reabilitar botão antes de fechar
                    $btn.prop('disabled', false).html('Salvar Receita');
                    
                    // Mostrar mensagem de sucesso
                    Toast.fire({
                        icon: 'success',
                        title: response.message || 'Receita salva com sucesso!'
                    });
                    
                    // Fechar modal
                    closeReceitaModal();
                } else {
                    alert('Erro: ' + (response.message || 'Não foi possível salvar a receita.'));
                    $btn.prop('disabled', false).html('Salvar Receita');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Erro ao salvar receita.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = 'Erro de validação: ' + JSON.stringify(xhr.responseJSON.errors);
                }
                alert(errorMessage);
                $btn.prop('disabled', false).html('Salvar Receita');
            }
        });
    });

    // Ao submeter o formulário principal (apenas para ativar/desativar produtos)
    // As receitas já são salvas individualmente via AJAX
    $('#formConfiguracao').on('submit', function(e) {
        try {
            console.log('[RASTREABILIDADE] Formulário principal sendo submetido...');
            
            // Limpar campos hidden anteriores de insumos (não são mais necessários)
            $('input[name^="produtos["][name*="[insumos]"]').remove();
            
            let produtosMarcados = 0;
            let produtosSemReceita = [];
            
            // Verificar produtos marcados
            $('.checkbox-rastreavel:checked').each(function() {
                const produtoId = $(this).data('produto-id');
                const produtoIdStr = String(produtoId);
                const ingredientes = receitasConfiguradas[produtoIdStr] || receitasConfiguradas[produtoId] || [];
                
                produtosMarcados++;
                
                // Verificar se tem receita configurada
                if (ingredientes.length === 0) {
                    produtosSemReceita.push(produtoId);
                }
            });
            
            // Se houver produtos marcados sem receita, avisar mas permitir salvar
            // (o usuário pode querer apenas desmarcar produtos)
            if (produtosSemReceita.length > 0 && produtosMarcados > 0) {
                if (!confirm('Alguns produtos estão marcados mas não têm receita configurada. Deseja continuar? Os produtos sem receita serão desativados.')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            console.log('[RASTREABILIDADE] Total de produtos marcados:', produtosMarcados);
        } catch (error) {
            console.error('[RASTREABILIDADE] Erro ao processar formulário:', error);
            e.preventDefault();
            alert('Erro ao processar formulário. Verifique o console para mais detalhes.');
            return false;
        }
    });
    
    // Debug: Log de erros gerais
    window.addEventListener('error', function(e) {
        console.error('[RASTREABILIDADE] Erro JavaScript capturado:', e.error);
        console.error('[RASTREABILIDADE] Mensagem:', e.message);
        console.error('[RASTREABILIDADE] Arquivo:', e.filename, 'Linha:', e.lineno);
    });
});

// Submeter formulário de busca ao mudar campo de busca ou paginação
$(document).ready(function() {
    const searchInput = document.getElementById('search');
    const paginacaoSelect = document.getElementById('paginacao');
    const formSearch = document.getElementById('formSearch');
    
    if (searchInput && formSearch) {
        // Submeter ao pressionar Enter no campo de busca
        $(searchInput).on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                formSearch.submit();
            }
        });
        
        // Submeter ao perder foco (blur) se o valor mudou
        let searchValue = searchInput.value;
        $(searchInput).on('blur', function() {
            if (this.value !== searchValue) {
                formSearch.submit();
            }
        });
    }
    
    if (paginacaoSelect && formSearch) {
        $(paginacaoSelect).on('change', function() {
            formSearch.submit();
        });
    }
});
</script>
@endpush
