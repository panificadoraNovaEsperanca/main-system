@extends('layouts.app')
@section('title', 'Cadastrar produção')

@section('content')

    <form enctype="multipart/form-data" action="{{ route('producao.store') }}" method="POST" id="formProducao">
        @csrf
        @if (isset($produto))
            @method('PUT')
        @endif

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
                                                class="form-control float-right dataHora" name="data_inicio[]">
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
            // Template HTML para nova linha
            function getNovaLinhaHTML(categoriaId) {
                return `
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
                            <input type="number" class="form-control quantidade" value="" name="quantidade[]" min="0">
                        </td>
                        <td>
                            <input autocomplete="off" type="text" value="" class="form-control float-right dataHora" name="data_inicio[]">
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

                $('.linhas-categoria').each(function() {
                    const categoriaId = $(this).data('categoria-id');
                    const categoriaNome = $(this).closest('.card').find('.btn').text().trim();
                    
                    $(this).find('.linha-producao').each(function(index) {
                        const produtoSelect = $(this).find('.produtos');
                        const quantidadeInput = $(this).find('.quantidade');
                        const dataInput = $(this).find('.dataHora');
                        
                        const produtoValor = produtoSelect.val();
                        const quantidadeValor = quantidadeInput.val();
                        const dataValor = dataInput.val();

                        // Se todos os campos estão vazios, ignora a linha
                        if (!produtoValor && !quantidadeValor && !dataValor) {
                            return true; // continua para próxima linha
                        }

                        // Validação do produto
                        if (!produtoValor) {
                            mensagens.push(`Linha ${index + 1} da categoria "${categoriaNome}": Produto é obrigatório`);
                            produtoSelect.addClass('is-invalid');
                            valido = false;
                        } else {
                            produtoSelect.removeClass('is-invalid');
                        }

                        // Validação da quantidade
                        if (!quantidadeValor || quantidadeValor <= 0) {
                            mensagens.push(`Linha ${index + 1} da categoria "${categoriaNome}": Quantidade deve ser maior que zero`);
                            quantidadeInput.addClass('is-invalid');
                            valido = false;
                        } else {
                            quantidadeInput.removeClass('is-invalid');
                        }

                        // Validação da data
                        if (!dataValor) {
                            mensagens.push(`Linha ${index + 1} da categoria "${categoriaNome}": Data de início é obrigatória`);
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

                // Verificar se há pelo menos uma linha preenchida
                const linhasPreenchidas = $('.linha-producao').filter(function() {
                    const produtoValor = $(this).find('.produtos').val();
                    const quantidadeValor = $(this).find('.quantidade').val();
                    const dataValor = $(this).find('.dataHora').val();
                    return produtoValor && quantidadeValor && dataValor;
                }).length;

                if (linhasPreenchidas === 0) {
                    mensagens.push('Pelo menos uma linha deve ser preenchida completamente');
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

            $('.dataHora').datetimepicker({
                format: 'd/m/Y H:i',
                lang: 'pt',
                validateOnBlur: false
            });

            // Adicionar nova linha
            $(document).on('click', '.btn-adicionar-linha', function() {
                const categoriaId = $(this).data('categoria-id');
                const tbody = $(this).prev('table').find('tbody');

                // Criar nova linha a partir do template
                const novaLinha = $(getNovaLinhaHTML(categoriaId));

                // Adicionar à tabela
                tbody.append(novaLinha);

                // Inicializar plugins na nova linha
                inicializarPluginsLinha(novaLinha);

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
                    const quantidade = $(this).find('.quantidade').val();
                    const dataInicio = $(this).find('.dataHora').val();
                    
                    // Remove a linha se estiver vazia
                    if (!produtoId || !quantidade || !dataInicio) {
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