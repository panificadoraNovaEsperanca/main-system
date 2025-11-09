@extends('layouts.app')
@section('title', 'Cadastrar produção')

@section('content')

    <form enctype="multipart/form-data" action="{{ route('producao.store') }}" method="POST" id="formProducao">
        @csrf
        @if (isset($produto))
            @method('PUT')
        @endif

        <div class="row mb-4">
            <div class="col-6">
                <div class="form-group">
                    <label>Data e Hora de Produção (será aplicada a todos os itens)</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input autocomplete="off" type="text" value=""
                            class="form-control float-right" id="dataProducaoGlobal" name="dataProducaoGlobal">
                    </div>
                    <small class="text-muted">Esta data será replicada para todos os itens adicionados</small>
                </div>
            </div>
        </div>

        <div id="accordion">
            @foreach ($categorias as $categoria)
                <div class="card card-primary">
                    <div class="card-header" id="heading{{ $categoria->id }}">
                        <h5 class="mb-0">
                            <button class="btn btn-block text-white text-left" style="font-size: 25px" type="button"
                                data-toggle="collapse" data-target="#collapse{{ $categoria->id }}" aria-expanded="true"
                                aria-controls="collapse{{ $categoria->id }}">
                                <b>{{ $categoria->nome }}</b>
                            </button>
                        </h5>
                    </div>

                    <div id="collapse{{ $categoria->id }}" class="collapse" aria-labelledby="heading{{ $categoria->id }}"
                        data-parent="#accordion">
                        <div class="card-body" style="background: #dedede">
                            <table class="table shadow rounded table-striped table-hover">
                                <thead class="bg-primary">
                                    <tr>
                                        <th>Produto</th>
                                        <th>Quantidade</th>
                                        <th>Data de Início</th>
                                        <th width="100">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="linhas-categoria" data-categoria-id="{{ $categoria->id }}"
                                    style="background: #fafafa">
                                    <!-- Linha vazia inicial -->
                                    <tr class="linha-producao">
                                        <td>
                                            <select class="custom-select produtos" name="produto_id[]">
                                                <option value="">Selecione um produto</option>
                                                @foreach ($categoria->produtos as $produto)
                                                    <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control quantidade" value=""
                                                name="quantidade[]" min="0">
                                        </td>
                                        <td>
                                            <input autocomplete="off" type="text" value=""
                                                class="form-control float-right dataHora" name="data_inicio[]" readonly>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger btn-remover-linha" disabled>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-success btn-adicionar-linha"
                                data-categoria-id="{{ $categoria->id }}">
                                <i class="fas fa-plus"></i> Adicionar Linha
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary mt-4">Salvar</button>
    </form>

    <script>
        $(document).ready(function() {
            // Template HTML para nova linha - CORRIGIDO
            function getNovaLinhaHTML(categoriaId) {
                // Encontrar a categoria correta e seus produtos
                const categoriaElement = $(`.linhas-categoria[data-categoria-id="${categoriaId}"]`);
                const primeiraLinha = categoriaElement.find('.linha-producao').first();
                
                // Clonar o select de produtos da primeira linha para manter todas as opções
                const selectOriginal = primeiraLinha.find('.produtos').first();
                let optionsHTML = '<option value="">Selecione um produto</option>';
                
                // Pegar todas as opções do select original, exceto a primeira (placeholder)
                selectOriginal.find('option').each(function() {
                    if ($(this).val() !== '') {
                        optionsHTML += `<option value="${$(this).val()}">${$(this).text()}</option>`;
                    }
                });
                
                return `
                    <tr class="linha-producao">
                        <td>
                            <select class="custom-select produtos" name="produto_id[]">
                                ${optionsHTML}
                            </select>
                        </td>
                        <td>
                            <input type="number" class="form-control quantidade" value="" name="quantidade[]" min="0">
                        </td>
                        <td>
                            <input autocomplete="off" type="text" value="" class="form-control float-right dataHora" name="data_inicio[]" readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger btn-remover-linha">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }

            // Função para validar data no formato dd/mm/yyyy HH:ii
            function validarData(data) {
                const regex = /^(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2})$/;
                if (!regex.test(data)) return false;
                
                const [, dia, mes, ano, hora, minuto] = data.match(regex);
                const date = new Date(ano, mes - 1, dia, hora, minuto);
                
                return date.getDate() == dia && 
                       date.getMonth() == mes - 1 && 
                       date.getFullYear() == ano;
            }

            // Validação do formulário
            function validarFormulario() {
                let valido = true;
                const mensagens = [];

                // Verificar se a data global foi preenchida primeiro
                const dataGlobal = $('#dataProducaoGlobal').val();
                if (!dataGlobal) {
                    mensagens.push('A data de produção no topo da página é obrigatória');
                    $('#dataProducaoGlobal').addClass('is-invalid');
                    valido = false;
                } else if (!validarData(dataGlobal)) {
                    mensagens.push('Data global inválida. Use o formato dd/mm/aaaa hh:mm');
                    $('#dataProducaoGlobal').addClass('is-invalid');
                    valido = false;
                } else {
                    $('#dataProducaoGlobal').removeClass('is-invalid');
                }

                $('.linhas-categoria').each(function() {
                    const categoriaId = $(this).data('categoria-id');
                    const categoriaNome = $(this).closest('.card').find('.btn').text().trim();
                    
                    $(this).find('.linha-producao').each(function(index) {
                        const produtoSelect = $(this).find('.produtos');
                        const quantidadeInput = $(this).find('.quantidade');
                        const dataInput = $(this).find('.dataHora');
                        
                        const produtoValor = produtoSelect.val();
                        const quantidadeValor = quantidadeInput.val()?.trim();
                        const dataValor = dataInput.val();

                        // Verifica se a linha está completamente vazia
                        // Considera vazia se não tem produto E não tem quantidade válida
                        // (a data pode estar preenchida pela data global, mas isso não conta como linha preenchida)
                        const temProduto = produtoValor && produtoValor !== '';
                        const temQuantidade = quantidadeValor && quantidadeValor !== '' && quantidadeValor !== '0' && parseFloat(quantidadeValor) > 0;
                        const linhaVazia = !temProduto && !temQuantidade;

                        // Se a linha está completamente vazia (sem produto e sem quantidade), ignora completamente
                        if (linhaVazia) {
                            // Remove classes de erro e continua
                            produtoSelect.removeClass('is-invalid');
                            quantidadeInput.removeClass('is-invalid');
                            dataInput.removeClass('is-invalid');
                            return true; // continua para próxima linha - NÃO VALIDA ESTA LINHA
                        }

                        // Se chegou aqui, a linha tem algum conteúdo, então valida tudo
                        // Validação do produto
                        if (!produtoValor) {
                            mensagens.push(`Linha ${index + 1} da categoria "${categoriaNome}": Produto é obrigatório`);
                            produtoSelect.addClass('is-invalid');
                            valido = false;
                        } else {
                            produtoSelect.removeClass('is-invalid');
                        }

                        // Validação da quantidade
                        if (!quantidadeValor || quantidadeValor === '' || quantidadeValor === '0' || parseFloat(quantidadeValor) <= 0) {
                            mensagens.push(`Linha ${index + 1} da categoria "${categoriaNome}": Quantidade deve ser maior que zero`);
                            quantidadeInput.addClass('is-invalid');
                            valido = false;
                        } else {
                            quantidadeInput.removeClass('is-invalid');
                        }

                        // Validação da data (já deve estar preenchida pela data global)
                        if (!dataValor) {
                            mensagens.push(`Linha ${index + 1} da categoria "${categoriaNome}": Data de início é obrigatória. Preencha o campo de data no topo da página.`);
                            dataInput.addClass('is-invalid');
                            valido = false;
                        } else if (!validarData(dataValor)) {
                            mensagens.push(`Linha ${index + 1} da categoria "${categoriaNome}": Data inválida. Use o formato dd/mm/aaaa hh:mm`);
                            dataInput.addClass('is-invalid');
                            valido = false;
                        } else {
                            dataInput.removeClass('is-invalid');
                        }
                    });
                });

                // Verificar se há pelo menos uma linha preenchida completamente
                const linhasPreenchidas = $('.linha-producao').filter(function() {
                    const produtoValor = $(this).find('.produtos').val();
                    const quantidadeValor = $(this).find('.quantidade').val()?.trim();
                    const dataValor = $(this).find('.dataHora').val();
                    return produtoValor && quantidadeValor && quantidadeValor !== '' && quantidadeValor !== '0' && parseFloat(quantidadeValor) > 0 && dataValor;
                }).length;

                if (linhasPreenchidas === 0 && valido) {
                    mensagens.push('Pelo menos uma linha deve ser preenchida completamente (produto e quantidade)');
                    valido = false;
                }

                if (!valido && mensagens.length > 0) {
                    alert('Erros de validação:\n\n' + mensagens.join('\n'));
                }

                return valido;
            }

            // Inicializar plugins nas linhas existentes
            function inicializarPluginsLinha(linha) {
                linha.find('.produtos').select2({
                    width: '100%'
                });

                linha.find('.dataHora').datetimepicker({
                    format: 'd/m/Y H:i',
                    lang: 'pt',
                    validateOnBlur: false
                });
            }

            // Inicializar plugins na primeira carga
            $('.produtos').select2({
                width: '100%'
            });

            // Campo global de data
            $('#dataProducaoGlobal').datetimepicker({
                format: 'd/m/Y H:i',
                lang: 'pt',
                validateOnBlur: false
            });

            // Replicar data global para todos os campos de data
            $('#dataProducaoGlobal').on('change', function() {
                const dataGlobal = $(this).val();
                $('.dataHora').val(dataGlobal);
            });

            $('.dataHora').datetimepicker({
                format: 'd/m/Y H:i',
                lang: 'pt',
                validateOnBlur: false
            });

            // Adicionar nova linha - CORRIGIDO
            $(document).on('click', '.btn-adicionar-linha', function() {
                const categoriaId = $(this).data('categoria-id');
                const tbody = $(this).prev('table').find('tbody');

                // Criar nova linha a partir do template CORRETO
                const novaLinha = $(getNovaLinhaHTML(categoriaId));

                // Adicionar à tabela
                tbody.append(novaLinha);

                // Inicializar plugins na nova linha
                inicializarPluginsLinha(novaLinha);

                // Aplicar data global se existir
                const dataGlobal = $('#dataProducaoGlobal').val();
                if (dataGlobal) {
                    novaLinha.find('.dataHora').val(dataGlobal);
                }

                // Habilitar botões de remover de todas as linhas
                tbody.find('.btn-remover-linha').prop('disabled', false);
            });

            // Remover linha
            $(document).on('click', '.btn-remover-linha', function() {
                const tr = $(this).closest('tr');
                const tbody = tr.closest('tbody');

                // Não remover se for a única linha
                if (tbody.find('tr').length > 1) {
                    // Destruir plugins antes de remover
                    tr.find('.produtos').select2('destroy');
                    tr.find('.dataHora').datetimepicker('destroy');
                    tr.remove();
                    
                    // Se só sobrou uma linha, desabilitar o botão de remover
                    if (tbody.find('tr').length === 1) {
                        tbody.find('.btn-remover-linha').prop('disabled', true);
                    }
                }
            });

            // Reorganizar nomes dos inputs antes do envio do formulário
            $('#formProducao').on('submit', function(e) {
                // Prevenir envio padrão
                e.preventDefault();

                // Validar formulário
                if (!validarFormulario()) {
                    return false;
                }

                // Remover linhas vazias antes do envio
                $('.linha-producao').each(function() {
                    const produtoId = $(this).find('.produtos').val();
                    const quantidade = $(this).find('.quantidade').val()?.trim();
                    
                    // Remove a linha se não tiver produto OU não tiver quantidade válida
                    // (não precisa verificar data pois ela é preenchida automaticamente)
                    const temProduto = produtoId && produtoId !== '';
                    const temQuantidade = quantidade && quantidade !== '' && quantidade !== '0' && parseFloat(quantidade) > 0;
                    
                    if (!temProduto || !temQuantidade) {
                        $(this).remove();
                    }
                });

                // Enviar formulário
                this.submit();
            });
        });
    </script>

    <style>
        .btn-adicionar-linha {
            margin-top: 10px;
        }

        .btn-remover-linha:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .table th {
            border-top: none;
        }

        .select2-container {
            width: 100% !important;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }
    </style>
@endsection