@extends('layouts.app')
@section('title', isset($pedido) ? "Editar pedido: $pedido->id" : 'Cadastrar ')

@push('styles')
<style>
  /* ============================================
     ESTILOS CUSTOMIZADOS - FORMULÁRIO DE PEDIDO
     ============================================ */
  
  /* Container principal */
  .pedido-form-container {
    padding: 20px 0;
  }
  
  /* Labels melhorados */
  .form-group label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
    font-size: 0.95rem;
  }
  
  /* Inputs melhorados */
  .form-control,
  .custom-select {
    border: 1.5px solid #dee2e6;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    transition: all 0.2s ease;
    font-size: 0.95rem;
  }
  
  .form-control:focus,
  .custom-select:focus {
    border-color: #3D2C1F;
    box-shadow: 0 0 0 0.2rem rgba(61, 44, 31, 0.15);
    outline: none;
  }
  
  /* Input group melhorado */
  .input-group-text {
    background-color: #f8f9fa;
    border: 1.5px solid #dee2e6;
    border-right: none;
    color: #6c757d;
  }
  
  .input-group .form-control {
    border-left: none;
  }
  
  .input-group .form-control:focus {
    border-left: 1.5px solid #3D2C1F;
  }
  
  /* Card de produtos melhorado */
  .card {
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 1.5rem;
  }
  
  .card-header {
    background: linear-gradient(135deg, #3D2C1F 0%, #5a4430 100%);
    color: white;
    border-radius: 8px 8px 0 0;
    padding: 1rem 1.25rem;
    border-bottom: none;
  }
  
  .card-header .card-title {
    margin: 0;
    font-weight: 600;
    font-size: 1.1rem;
  }
  
  .card-body {
    padding: 1.5rem;
  }
  
  /* Botão Adicionar Produto melhorado */
  #addProduto {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    border-radius: 6px;
    padding: 0.6rem 1.2rem;
    font-weight: 600;
    box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
    transition: all 0.2s ease;
    white-space: nowrap;
  }
  
  #addProduto:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
  }
  
  #addProduto:active {
    transform: translateY(0);
  }
  
  /* Tabela de produtos - usa padrão global, apenas ajustes específicos */
  #produtos {
    /* Herda estilos do .table global */
  }
  
  #produtos tfoot td {
    text-align: right;
  }
  
  /* Botão Excluir melhorado */
  .killme {
    padding: 0.4rem 0.8rem;
    font-size: 0.85rem;
    border-radius: 4px;
    border: none;
    transition: all 0.2s ease;
  }
  
  .killme:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(220, 53, 69, 0.3);
  }
  
  /* Botão Salvar - usa padrão global, apenas margens específicas */
  button[type="submit"] {
    margin-top: 2rem;
    margin-bottom: 2rem;
    width: auto;
    min-width: 120px;
  }
  
  /* Checkbox melhorado */
  .custom-control-input:checked ~ .custom-control-label::before {
    background-color: #3D2C1F;
    border-color: #3D2C1F;
  }
  
  .custom-control-label {
    font-weight: 500;
    color: #495057;
    cursor: pointer;
  }
  
  /* Mensagens de erro melhoradas */
  .text-red {
    color: #dc3545 !important;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: block;
  }
  
  /* Select2 melhorado */
  .select2-container--default .select2-selection--single {
    border: 1.5px solid #dee2e6;
    border-radius: 6px;
    height: 38px;
    padding: 0.25rem 0;
  }
  
  .select2-container--default .select2-selection--single:focus {
    border-color: #3D2C1F;
  }
  
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    padding-left: 12px;
  }
  
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
    right: 8px;
  }
  
  /* Espaçamento melhorado */
  .form-group {
    margin-bottom: 1.25rem;
  }
  
  .row {
    margin-bottom: 0;
  }
  
  /* Textarea melhorado */
  textarea.form-control {
    resize: vertical;
    min-height: 60px;
  }
  
  /* Inputs numéricos melhorados */
  input[type="number"] {
    text-align: right;
  }
  
  input[type="number"]:disabled {
    background-color: #e9ecef;
    cursor: not-allowed;
  }
  
  /* Responsividade */
  @media (max-width: 768px) {
    .card-body {
      padding: 1rem;
    }
    
    #addProduto {
      width: 100%;
      margin-bottom: 1rem;
    }
    
    #produtos {
      font-size: 0.85rem;
    }
    
    #produtos thead th,
    #produtos tbody td {
      padding: 0.5rem;
    }
  }
</style>
@endpush

@section('content')
  <form enctype="multipart/form-data"
    action="{{ isset($pedido) ? route('pedido.update', $pedido->id) : route('pedido.store') }}" method="POST">
    @csrf
    @if (isset($pedido))
      @method('PUT')
    @endif
    <div class="row">
      <div class="col-6">
        <div class="form-group">
          <label for="">Cliente</label>
          <div class="input-group  ">
            <select class="" id="cliente" name="cliente">
              @if (isset($pedido))
                <option selected value="{{ $pedido->cliente->id }}-{{ $pedido->cliente->tipo_cliente }}">
                  {{ $pedido->cliente->name }}</option>
              @elseif(old('cliente'))
                <option selected value="{{ old('cliente') }}">{{ old('cliente') }}</option>
              @endif
            </select>
          </div>
          @error('cliente')
            <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
          @enderror
        </div>
      </div>
      <div class="col-6">
        <div class="form-group">
          <label for="">Motorista</label>
          <div class="input-group  ">
            <select class="" id="motorista" name="motorista">
              @if (isset($pedido))
                <option selected value="{{ $pedido->motorista->id }}">
                  {{ $pedido->motorista->nome }}</option>
              @elseif(old('motorista'))
                <option selected value="{{ old('motorista') }}">{{ old('motorista') }}</option>
              @endif
            </select>
          </div>
          @error('motorista')
            <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
          @enderror
        </div>
      </div>
      <div class="col-6">
        <div class="form-group">
          <label for="dataHora">Data e Hora da entrega</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">
                <i class="far fa-calendar-alt"></i>
              </span>
            </div>
            <input autocomplete="off" type="text" value="{{ old('dataHora', isset($pedido) ? $pedido->dt_previsao_formatted : '') }}"
              class="form-control float-right" id="dataHora" name="dataHora">

          </div>
          @error('dataHora')
            <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
          @enderror
        </div>
      </div>
      <div class="col-6">
        <div class="form-group">
          <label>Status</label>
          <div class="input-group">
            <select class="custom-select" {{ !isset($pedido) ? 'disabled' : '' }} name="status">
              <option hidden>Selecione uma opção</option>
              <option
                {{ old('status', isset($pedido) ? $pedido->status : 'AGENDADO') == 'AGENDADO' ? 'selected' : '' }}
                value="AGENDADO">Agendado
              </option>
              <option {{ old('status', isset($pedido) ? $pedido->status : '') == 'ENTREGUE' ? 'selected' : '' }} value="ENTREGUE">Entregue
              </option>
              <option {{ old('status', isset($pedido) ? $pedido->status : '') == 'CANCELADO' ? 'selected' : '' }} value="CANCELADO">
                Cancelado</option>

            </select>
          </div>
          @error('status')
            <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
          @enderror
        </div>
      </div>

      <div class="w-100 card">

        <div class="card-header">
          <h3 class="card-title">Produtos</h3>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-12">
              <button id="addProduto" type="button" class="btn btn-success">Adicionar Produto</button>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table" id="produtos">
              <thead>
                <tr>
                  <th scope="col">Produto</th>
                  <th scope="col">Quantidade</th>
                  <th scope="col">Valor Unitário</th>
                  <th scope="col">Observação</th>
                  <th scope="col">Total</th>
                  <th scope="col" style="width: 100px;">Ações</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $total = 0;
                @endphp
                @if (isset($pedido))
                  @foreach ($pedido->produtos as $produtosEscolhidos)
                    @php
                      $total += $produtosEscolhidos->quantidade * $produtosEscolhidos->preco;
                    @endphp
                    <tr data-id="{{ $loop->index }}">
                      <td>
                        <select class="custom-select produtos" data-id="{{ $loop->index }}" name="produto[]">
                          <option hidden>Selecione uma opção</option>
                          @foreach ($produtos as $produto)
                            <option {{ $produtosEscolhidos->produto_id == $produto->id ? 'selected' : '' }}
                              value="{{ $produto->id }}">{{ $produto->nome }}</option>
                          @endforeach
                        </select>
                      </td>

                      <td><input type="number" step="0.1" class="quantidadeProduto form-control " data-id="{{ $loop->index }}"
                          value="{{ $produtosEscolhidos->quantidade }}" name="quantidade[]"></td>
                      <td><input type="number" step="0.1"
                          {{ $pedido->cliente->tipo_cliente == 'h' ? '' : 'disabled' }} class="form-control"
                          id="precoProduto-{{ $loop->index }}" value="{{ $produtosEscolhidos->preco }}"
                          data-id="{{ $loop->index }}" name="precoProduto[]"></td>
                      <td>
                        <textarea rows="2" type="text" class="observacao form-control" data-id="{{ $loop->index }}"
                          name="observacao[]" style="white-space: pre-wrap; word-wrap: break-word;">{{ $produtosEscolhidos->observacao }}</textarea>
                      </td>

                      <td><input type="number" step="0.1" disabled class="form-control"
                          id="valorCalculado-{{ $loop->index }}"
                          value="{{ $produtosEscolhidos->quantidade * $produtosEscolhidos->preco }}">
                      </td>
                      <td><button class="btn btn-danger killme" data-id="{{ $produtosEscolhidos->id }}"
                          type="button">Excluir</button></td>

                    </tr>
                  @endforeach
                @endif
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="4" style="text-align: right; font-weight: 600; padding-right: 1rem;">Total Geral:</td>
                  <td id="totalProdutos" style="font-weight: 700; font-size: 1.1rem;">{{ number_format($total, 2, ',', '.') }}</td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

      </div>
      <div class="col-sm-6 mt-3">

        <div class="form-group clearfix">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" data-ok="0" class="custom-control-input" name="repete" id="repete" {{ old('repete') ? 'checked' : '' }}>
            <label class="custom-control-label" for="repete">Replicar pedido?</label>
          </div>
        </div>
      </div>
      <div class="col-12" id="repeticao" style="display: none">
        <div class="row">
          <div class="col-9 ">
            <div class="form-group">
              <label for="">Datas para cópia</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                  </span>
                </div>
                <input autocomplete="off" type="text" class="form-control float-right" name="periodo"
                  id="periodo" value="{{ old('periodo') }}">

              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    </div>


    <input type="hidden" id="cliente_id" name="cliente_id" value="{{ old('cliente_id', isset($pedido) ? $pedido->cliente_id : '') }}">
    
    <div class="row" style="margin-top: 2rem; margin-bottom: 2rem;">
      <div class="col-12">
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-2"></i>Salvar Pedido
          </button>
        </div>
      </div>
    </div>

  </form>

  <input type="hidden" id="produtosCatalogo" value="{{ json_encode($produtos) }}">
  <input type="hidden" id="oldProdutos" value="{{ json_encode(old('produto', [])) }}">
  <input type="hidden" id="oldQuantidades" value="{{ json_encode(old('quantidade', [])) }}">
  <input type="hidden" id="oldPrecos" value="{{ json_encode(old('precoProduto', [])) }}">
  <input type="hidden" id="oldObservacoes" value="{{ json_encode(old('observacao', [])) }}">
  <script></script>
@endsection

@push('scripts')
  <script type="module">
    $(document).ready(function() {
      $(`.produtos`).select2({
        width: '100%'
      })

      // Restaurar produtos após erro de validação
      function restaurarProdutos() {
        let oldProdutos = JSON.parse($('#oldProdutos').val() || '[]');
        let oldQuantidades = JSON.parse($('#oldQuantidades').val() || '[]');
        let oldPrecos = JSON.parse($('#oldPrecos').val() || '[]');
        let oldObservacoes = JSON.parse($('#oldObservacoes').val() || '[]');
        
        if (oldProdutos.length > 0 && (tipo_cliente != '' || $('#cliente_id').val() != '')) {
          oldProdutos.forEach((produtoId, index) => {
            if (produtoId && produtoId != '0' && produtoId != 0) {
              let produtos = JSON.parse($('#produtosCatalogo').val());
              // Converter para número para comparação
              let produtoIdNum = parseInt(produtoId);
              let produto = produtos.find(p => p.id == produtoIdNum || p.id == produtoId);
              if (produto) {
                let id = $('#produtos tbody tr').length;
                let precoLiberado = tipo_cliente == 'h';
                let selectProdutos = `<select class="custom-select produtos " id="select2-${id}" data-id="${id}" name="produto[]" >
                        <option value="0" hidden>Selecione uma opção</option>`;
                for (let p of produtos) {
                  selectProdutos += `<option ${p.id == produtoId ? 'selected' : ''} value="${p.id}">${p.id} - ${p.nome}</option>`;
                }
                selectProdutos += "</select>"
                
                let quantidade = oldQuantidades[index] || '';
                let preco = oldPrecos[index] || (tipo_cliente && tipo_cliente != 'h' ? (produto.precos && produto.precos[tipo_cliente] ? produto.precos[tipo_cliente] : '') : '');
                let observacao = oldObservacoes[index] || '';
                let total = quantidade && preco ? (parseFloat(quantidade) * parseFloat(preco)) : 0;
                
                let tr = `<tr data-id="${id}">
                                <td>${selectProdutos}</td>
                                <td><input type="number" step="0.1" class="quantidadeProduto form-control " data-id="${id}" id="quantidade-${id}" name="quantidade[]" value="${quantidade}"></td>
                                <td><input type="number" step="0.1" ${precoLiberado ? '' : 'disabled'} class="form-control precoProduto" id="precoProduto-${id}" data-id="${id}" name="precoProduto[]" value="${preco}"></td>
                                <td><textarea rows="2" type="text" class="observacao form-control" data-id="${id}" name="observacao[]" style="white-space: pre-wrap; word-wrap: break-word;">${observacao}</textarea></td>
                                <td><input type="number" step="0.1" disabled class="form-control" id="valorCalculado-${id}" value="${total}"></td>
                                <td><button class="btn btn-danger killme" data-id="0" type="button">Excluir</button></td>
                        </tr>`;
                $('#produtos tbody').append(tr);
                $(`#select2-${id}`).select2({
                  width: '100%'
                });
                
                // Se não houver preço e o produto tiver preços no array, usar do array
                if (!preco && tipo_cliente && tipo_cliente != 'h' && produto.precos && produto.precos[tipo_cliente]) {
                  let precoDoArray = produto.precos[tipo_cliente].toString().replace(',', '.');
                  $(`#precoProduto-${id}`).val(precoDoArray);
                  // Recalcular total
                  let quantidadeAtual = $(`#quantidade-${id}`).val();
                  if (quantidadeAtual) {
                    let precoTotal = parseFloat(quantidadeAtual) * parseFloat(precoDoArray);
                    $(`#valorCalculado-${id}`).val(precoTotal);
                    let totalGeral = 0;
                    $('#produtos tbody tr').each(function() {
                      let rowId = $(this).data('id');
                      let valor = $(`#valorCalculado-${rowId}`).val() || 0;
                      totalGeral += parseFloat(valor);
                    });
                    $('#totalProdutos').text(totalGeral.toFixed(2).replace('.', ','));
                  }
                }
              }
            }
          });
          
          // Recalcular total
          let total = 0;
          $('#produtos tbody tr').each(function() {
            let id = $(this).data('id');
            let valor = $(`#valorCalculado-${id}`).val() || 0;
            total += parseFloat(valor);
          });
          $('#totalProdutos').text(total.toFixed(2).replace('.', ','));
        }
      }
      
      @if(!isset($pedido) && old('produto') && !old('cliente'))
        // Se não houver cliente para restaurar, tentar restaurar produtos diretamente
        setTimeout(function() {
          if (tipo_cliente != '' || $('#cliente_id').val() != '') {
            restaurarProdutos();
          }
        }, 1000);
      @endif
    });
    @php
      $tipoClienteOld = '';
      if (old('cliente') && !isset($pedido)) {
        $clienteParts = explode('-', old('cliente'));
        $tipoClienteOld = $clienteParts[1] ?? '';
      }
      $tipoCliente = $tipoClienteOld ?: (isset($pedido) ? $pedido->cliente->tipo_cliente : '');
    @endphp
    let tipo_cliente = '{{ $tipoCliente }}';
    
    // Restaurar cliente selecionado se houver old()
    @if(old('cliente') && !isset($pedido))
      $(document).ready(function() {
        let clienteOld = '{{ old('cliente') }}';
        if (clienteOld) {
          let clienteParts = clienteOld.split('-');
          $('#cliente_id').val(clienteParts[0]);
          tipo_cliente = clienteParts[1] || '';
          
          // Aguardar select2 estar pronto e então definir o valor
          setTimeout(function() {
            $('#cliente').val(clienteOld).trigger('change');
          }, 500);
        }
      });
    @endif
    
    $('#cliente').on('change', function() {
      let cliente = this.value.split('-')
      $('#cliente_id').val(cliente[0])
      tipo_cliente = cliente[1];
    })
    $(document).on('click', '.killme', function() {
      const id = this.dataset.id;
      if (id == 0) {
        this.parentNode.parentNode.remove()
      } else if (id != undefined && this.dataset.id != null && this.dataset.id != '') {
        fetch(`/removeProdutoPedido/${id}`, {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-Token": $('meta[name="csrf-token"]').attr('content')
          },
        }).then((response) => {
          response.json().then((res) => {
            if (res.success) {
              Toast.fire({
                icon: 'success',
                title: res.message
              });
            }
            this.parentNode.parentNode.remove()

          });

        }).catch((error) => {
          console.log(error)
          Toast.fire({
            icon: 'success',
            title: 'Erro ao excluir item!'
          });
        });
      }
    })
    // Restaurar estado do checkbox repete
    @if(old('repete'))
      $('#repete').prop('checked', true);
      $('#repeticao').show();
    @endif
    
    $('#repete').on('change', function() {
      if ($(this).is(':checked')) {
        $('#repeticao').fadeIn('fast', function() {

        })
      } else {
        $('#repeticao').fadeOut('fast', function() {
          $('#periodo').val('')
        })
      }
    })

    $(document).on('change', '.produtos', function(e) {
      let produtoId = this.value;
      let id = this.dataset.id;
      let produtos = JSON.parse($('#produtosCatalogo').val());
      let produto = produtos.find((element) => element.id == produtoId || element.id == parseInt(produtoId));
      
      if (produto && produto.precos && produto.precos[tipo_cliente]) {
        // Converter vírgula para ponto no preço
        let preco = produto.precos[tipo_cliente].toString().replace(',', '.');
        $(`#precoProduto-${id}`).val(preco);
        
        // Recalcular total se houver quantidade
        let quantidade = $(`#quantidade-${id}`).val();
        if (quantidade) {
          let precoTotal = parseFloat(quantidade) * parseFloat(preco);
          $(`#valorCalculado-${id}`).val(precoTotal);
          
          // Recalcular total geral
          let total = 0;
          $('#produtos tbody tr').each(function() {
            let rowId = $(this).data('id');
            let valor = $(`#valorCalculado-${rowId}`).val() || 0;
            total += parseFloat(valor);
          });
          $('#totalProdutos').text(total.toFixed(2).replace('.', ','));
        }
      }
    })


    $(document).on('select2:selecting', '.produtos', function(e) {
      var data = e.params.args.data;
      let idSelected = data.id
      $(`.produtos`).each((index, element) => {
        if (idSelected == element.value) {
          Toast.fire({
            icon: 'error',
            title: 'Produto já selecionado!'
          });
          $(this).val('').trigger('change')
        }
      })
    })

    $(document).on('change', '.quantidadeProduto', function() {
      let id = this.dataset.id;
      let preco = $(`#precoProduto-${id}`).val()
      if (preco != '') {
        let precoTotal = parseInt(this.value) * parseFloat(preco)
        $(`#valorCalculado-${id}`).val(precoTotal)
        let totalRows = $('#produtos tbody tr').length;
        let total = 0;
        for (let a = 0; a <= totalRows - 1; a++) {
          let valor = $(`#valorCalculado-${a}`).val() == '' || $(`#valorCalculado-${a}`).val() ==
            undefined ? 0 : $(`#valorCalculado-${a}`).val()
          total += parseFloat(valor);
        }
        $('#totalProdutos').text(total.toFixed(2).replace('.', ','));

      }
    })
    $(document).on('change', '.precoProduto', function() {
      let id = this.dataset.id;
      let quantidade = $(`#quantidade-${id}`).val()
      if (quantidade != '') {

        let precoTotal = parseInt(quantidade) * parseFloat(this.value)
        $(`#valorCalculado-${id}`).val(precoTotal)
        let totalRows = $('#produtos tbody tr').length;
        let total = 0;
        for (let a = 0; a <= totalRows - 1; a++) {
          let valor = $(`#valorCalculado-${a}`).val() == '' || $(`#valorCalculado-${a}`).val() ==
            undefined ? 0 : $(`#valorCalculado-${a}`).val()
          total += parseFloat(valor);
        }
        $('#totalProdutos').text(total.toFixed(2).replace('.', ','));

      }
    })



    $('#addProduto').on('click', async function() {
      if (tipo_cliente != '' || $('#cliente_id').val() != '') {
        let precoLiberado = tipo_cliente == 'h'
        let produtos = JSON.parse($('#produtosCatalogo').val());
        let id = $('#produtos tbody tr').length;
        let selectProdutos = `<select class="custom-select produtos " id="select2-${id}" data-id="${id}" name="produto[]" >
                <option value="0" hidden>Selecione uma opção</option>`;
        for (let produto of produtos) {
          if (produtos.length == 1) {
            selectProdutos +=
              `<option selected value="${produto.id}">${produto.id} - ${produto.nome}</option>`;
          } else {
            selectProdutos += `<option value="${produto.id}">${produto.id} - ${produto.nome}</option>`;

          }
        }
        selectProdutos += "</select>"
        let tr = `<tr data-id="${id}">
                            <td>${selectProdutos}</td>
                            <td><input  type="number" step="0.1" class="quantidadeProduto form-control " data-id="${id}" id="quantidade-${id}" name="quantidade[]"></td>
                            <td><input  type="number" step="0.1" ${precoLiberado ? '':'disabled'} class="form-control precoProduto" id="precoProduto-${id}" data-id="${id}" name="precoProduto[]"></td>
                            <td><textarea rows="2" type="text" class="observacao form-control" data-id="${id}" name="observacao[]" style="white-space: pre-wrap; word-wrap: break-word;"></textarea></td>
                            <td><input  type="number" step="0.1" disabled class="form-control" id="valorCalculado-${id}" value="0"></td>
                            <td><button class="btn btn-danger killme" data-id="0" type="button" >Excluir</button></td>
                    </tr>
                    `
        $('#produtos tbody').append(tr)
        if (produtos.length == 1) {
          let produtoUnico = produtos[0];
          // Usar preços do array local
          if (produtoUnico.precos && produtoUnico.precos[tipo_cliente]) {
            let preco = produtoUnico.precos[tipo_cliente].toString().replace(',', '.');
            $(`#precoProduto-${id}`).val(preco);
          }
        }
        $(`#select2-${id}`).select2({
          width: '100%'
        })

      } else {
        Toast.fire({
          icon: 'error',
          title: 'Escolha o cliente!'
        });
      }

    })



    $('#dataHora').datetimepicker({
      i18n: {
        de: {

        }
      },
      format: 'd/m/Y H:i',
      lang: 'pt'
    });
    $('#horario').datetimepicker({
      datepicker: false,
      format: 'H:i',
      lang: 'pt'
    });
    $('#periodo').datepicker({
      multidate: true,

      format: 'dd/mm/yyyy',
      lang: 'pt'
    });
    // Preparar opção inicial se houver old()
    @if(old('cliente') && !isset($pedido))
      @php
        $clienteParts = explode('-', old('cliente'));
        $clienteId = $clienteParts[0] ?? '';
        $clienteTipo = $clienteParts[1] ?? '';
        $cliente = \App\Models\Cliente::find($clienteId);
      @endphp
      @if($cliente)
        var clienteInicial = {
          id: {!! json_encode(old('cliente')) !!},
          text: {!! json_encode($cliente->name) !!}
        };
      @else
        var clienteInicial = {
          id: {!! json_encode(old('cliente')) !!},
          text: {!! json_encode(old('cliente')) !!}
        };
      @endif
    @else
      var clienteInicial = null;
    @endif
    
    var clienteSelect2Config = {
      width: "100%",
      ajax: {
        url: '/clientsByName',
        dataType: "json",
        type: "GET",
        delay: 450,
        data: function(params) {
          var queryParameters = {
            nome: params.term
          }
          return queryParameters;
        },
        processResults: function(data) {
          return {
            results: $.map(data.data, function(item) {
              return {
                text: item.name,
                id: `${item.id}-${item.tipo_cliente}`,
              }
            })
          };
        }
      }
    };
    
    // Adicionar opção inicial se houver old()
    @if(old('cliente') && !isset($pedido))
      if (clienteInicial) {
        clienteSelect2Config.data = [clienteInicial];
      }
    @endif
    
    $('#cliente').select2(clienteSelect2Config);
    
    // Restaurar cliente selecionado após select2 estar pronto
    @if(old('cliente') && !isset($pedido))
      setTimeout(function() {
        let clienteOld = {!! json_encode(old('cliente')) !!};
        if (clienteOld && clienteInicial) {
          let clienteParts = clienteOld.split('-');
          $('#cliente_id').val(clienteParts[0]);
          tipo_cliente = clienteParts[1] || '';
          
          $('#cliente').val(clienteOld).trigger('change');
          
          // Após cliente ser restaurado, restaurar produtos
          setTimeout(function() {
            restaurarProdutos();
          }, 300);
        }
      }, 500);
    @endif

    // Preparar opção inicial do motorista se houver old()
    @if(old('motorista') && !isset($pedido))
      @php
        $motorista = \App\Models\Motorista::find(old('motorista'));
      @endphp
      @if($motorista)
        var motoristaInicial = {
          id: {!! json_encode(old('motorista')) !!},
          text: {!! json_encode($motorista->nome . ' - ' . $motorista->turno) !!}
        };
      @else
        var motoristaInicial = {
          id: {!! json_encode(old('motorista')) !!},
          text: {!! json_encode(old('motorista')) !!}
        };
      @endif
    @else
      var motoristaInicial = null;
    @endif
    
    var motoristaSelect2Config = {
      width: "100%",
      ajax: {
        url: '/motoristaByName',
        dataType: "json",
        type: "GET",
        delay: 450,
        data: function(params) {
          var queryParameters = {
            nome: params.term
          }
          return queryParameters;
        },
        processResults: function(data) {
          return {
            results: $.map(data.data, function(item) {
              return {
                text: `${item.nome} - ${item.turno} `,
                id: item.id
              }
            })
          };
        }
      }
    };
    
    // Adicionar opção inicial se houver old()
    @if(old('motorista') && !isset($pedido))
      if (motoristaInicial) {
        motoristaSelect2Config.data = [motoristaInicial];
      }
    @endif
    
    $('#motorista').select2(motoristaSelect2Config);
    
    // Restaurar motorista selecionado após select2 estar pronto
    @if(old('motorista') && !isset($pedido))
      setTimeout(function() {
        let motoristaOld = {!! json_encode(old('motorista')) !!};
        if (motoristaOld && motoristaInicial) {
          $('#motorista').val(motoristaOld).trigger('change');
        }
      }, 500);
    @endif
  </script>
@endpush

