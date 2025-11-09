@extends('layouts.app')
@section('title', 'Relatório de motoristas')

@section('content')

    <form enctype="multipart/form-data" id="formRelatorio" action="{{ route('motorista.relatorio') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="">Motorista</label>
                    <div class="input-group  ">
                        <select class="" id="motorista" name="motorista">

                        </select>
                    </div>
                    @error('motorista')
                        <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                    @enderror
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label>Dia da entrega</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input type="text" autocomplete="off" value="" class="form-control float-right"
                            id="data" name="data">

                    </div>
                    @error('data')
                        <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                    @enderror
                </div>
            </div>
        </div>
        
        <button data-action="{{ route('motorista.relatorio') }}" type="submit" class="btn btn-primary mt-4">Emitir</button>
        <button data-action="{{ route('etiquetas') }}" type="submit" class="btn btn-primary mt-4">Baixar etiquetas (ZPL)</button>
        <button type="button" id="btnImprimirQZ" class="btn btn-success mt-4">Imprimir via QZ Tray</button>
        <button type="button" id="btnImprimirTeste" class="btn btn-warning mt-4">Teste 5 Etiquetas (QZ)</button>
    </form>

    <!-- Modal para seleção de impressora -->
    <div class="modal fade" id="impressoraModal" tabindex="-1" role="dialog" aria-labelledby="impressoraModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="impressoraModalLabel">Selecionar Impressora</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="selectImpressora">Impressora:</label>
                        <select class="form-control" id="selectImpressora">
                            <option value="PDF">PDF (Teste)</option>
                            <option value="ETIQUETADEIRA">ETIQUETADEIRA</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="checkModoTeste">
                        <label class="form-check-label" for="checkModoTeste">Modo Teste (5 etiquetas)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmarImpressao">Imprimir</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qz-tray@2.1.1"></script>
    <script>
        document.querySelectorAll('#formRelatorio button[type=submit]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const form = document.getElementById('formRelatorio');
                form.action = this.getAttribute('data-action');
            });
        });

        // Configuração do QZ Tray
        qz.security.setCertificatePromise(function(resolve, reject) {
            resolve("-----BEGIN CERTIFICATE-----\n" +
                "MIIBpzCCARACCQC1dJeM5nUF9jANBgkqhkiG9w0BAQUFADAeMRwwGgYDVQQDDBNR\n" +
                "eiBUcmF5IFNpZ25lciAodGVzdCkwHhcNMTUwMjEyMDAwMDAwWhcNMzUwMjEyMDAw\n" +
                "MDAwWjAeMRwwGgYDVQQDDBNReiBUcmF5IFNpZ25lciAodGVzdCkwgZ8wDQYJKoZI\n" +
                "hvcNAQEBBQADgY0AMIGJAoGBAJ4dVe+OPR1b2k2eA6w8rY0wY7ijm3H5Q8NnJqZ\n" +
                "lzCC9Qv8lV9cO6kXq2m7CjJdXHWD4i1K0P25K1Hl2rF7N8V5q+4JqNQ5Qy5Q5Q5\n" +
                "Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5\n" +
                "Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5\n" +
                "AgMBAAEwDQYJKoZIhvcNAQEFBQADgYEAKJ8v+8J+8J+8J+8J+8J+8J+8J+8J+8J\n" +
                "+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8\n" +
                "J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8\n" +
                "J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8\n" +
                "J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8\n" +
                "-----END CERTIFICATE-----");
        });

        qz.security.setSignaturePromise(function(toSign) {
            return function(resolve, reject) {
                resolve("-----BEGIN SIGNATURE-----\n" +
                    "MIIBpzCCARACCQC1dJeM5nUF9jANBgkqhkiGw0BAQUFADAeMRwwGgYDVQQDDBNR\n" +
                    "eiBUcmF5IFNpZ25lciAodGVzdCkwHhcNMTUwMjEyMDAwMDAwWhcNMzUwMjEyMDAw\n" +
                    "MDAwWjAeMRwwGgYDVQQDDBNReiBUcmF5IFNpZ25lciAodGVzdCkwgZ8wDQYJKoZI\n" +
                    "hvcNAQEBBQADgY0AMIGJAoGBAJ4dVe+OPR1b2k2eA6w8rY0wY7ijm3H5Q8NnJqZ\n" +
                    "lzCC9Qv8lV9cO6kXq2m7CjJdXHWD4i1K0P25K1Hl2rF7N8V5q+4JqNQ5Qy5Q5Q5\n" +
                    "Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5\n" +
                    "Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5\n" +
                    "AgMBAAEwDQYJKoZIhvcNAQEFBQADgYEAKJ8v+8J+8J+8J+8J+8J+8J+8J+8J+8J\n" +
                    "+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8\n" +
                    "J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8\n" +
                    "J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8\n" +
                    "J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8\n" +
                    "-----END SIGNATURE-----");
            };
        });

        // Variável para controlar se já estamos conectados
        let qzConnected = false;

        // Função para conectar ao QZ Tray apenas quando necessário
        async function conectarQZTray() {
            if (qzConnected) {
                return true;
            }

            try {
                await qz.websocket.connect();
                qzConnected = true;
                console.log('QZ Tray conectado com sucesso!');
                return true;
            } catch (error) {
                console.warn('QZ Tray não está disponível:', error.message);
                return false;
            }
        }

        // Função para obter dados ZPL
        async function obterDadosZPL(modoTeste = false) {
            const form = document.getElementById('formRelatorio');
            const formData = new FormData(form);
            
            if (modoTeste) {
                formData.append('teste', '1');
            }

            try {
                const response = await fetch("{{ route('etiquetas') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'text/plain'
                    },
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Erro ao obter dados ZPL');
                }

                const zplData = await response.text();
                
                if (zplData.includes('<!DOCTYPE html>') || zplData.includes('<html')) {
                    throw new Error('Resposta contém HTML em vez de ZPL. Verifique a rota.');
                }
                
                console.log('Dados ZPL recebidos:', zplData.substring(0, 100) + '...');
                return zplData;
                
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao gerar etiquetas: ' + error.message);
                return null;
            }
        }

        // Função para imprimir via QZ Tray
        async function imprimirViaQZTray(impressora, modoTeste = false) {
            try {
                // Primeiro obter os dados ZPL
                const zplData = await obterDadosZPL(modoTeste);
                
                if (!zplData) {
                    return;
                }

                // Só conectar ao QZ Tray quando for realmente imprimir
                const conectado = await conectarQZTray();
                if (!conectado) {
                    alert('Não foi possível conectar ao QZ Tray. Verifique se o QZ Tray está executando.');
                    return;
                }

                // Configurar impressora
                const config = qz.configs.create(impressora);
                const data = [zplData];

                // Imprimir
                await qz.print(config, data);
                
                alert('Etiquetas enviadas para impressão com sucesso!');
                
            } catch (error) {
                console.error('Erro na impressão:', error);
                
                if (error.message.includes('WebSocket') || error.message.includes('connection')) {
                    qzConnected = false; // Resetar flag de conexão
                    alert('Erro de conexão com QZ Tray. Verifique se o QZ Tray está executando e tente novamente.');
                } else {
                    alert('Erro na impressão: ' + error.message);
                }
            }
        }

        // Event Listeners
        document.getElementById('btnImprimirQZ').addEventListener('click', function() {
            $('#impressoraModal').modal('show');
        });

        document.getElementById('btnImprimirTeste').addEventListener('click', function() {
            imprimirViaQZTray('PDF', true);
        });

        document.getElementById('btnConfirmarImpressao').addEventListener('click', function() {
            const impressora = document.getElementById('selectImpressora').value;
            const modoTeste = document.getElementById('checkModoTeste').checked;
            
            $('#impressoraModal').modal('hide');
            imprimirViaQZTray(impressora, modoTeste);
        });

        // Inicializar datetimepicker e select2
        $('#data').datetimepicker({
            i18n: {
                de: {
                    months: [
                        'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                    ],
                    dayOfWeek: [
                        'Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'
                    ]
                }
            },
            format: 'd/m/Y',
            lang: 'pt'
        });

        $('#motorista').select2({
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
        });

        // Função para descobrir impressoras disponíveis (só quando necessário)
        async function descobrirImpressoras() {
            try {
                const conectado = await conectarQZTray();
                if (!conectado) {
                    return;
                }
                
                const impressoras = await qz.printers.find();
                const selectImpressora = document.getElementById('selectImpressora');
                
                // Limpar opções existentes, mas manter as opções padrão
                const opcoesPadrao = [
                    { value: 'PDF', text: 'PDF (Teste)' },
                    { value: 'ETIQUETADEIRA', text: 'ETIQUETADEIRA' }
                ];
                
                selectImpressora.innerHTML = '';
                
                // Adicionar opções padrão
                opcoesPadrao.forEach(opcao => {
                    const option = document.createElement('option');
                    option.value = opcao.value;
                    option.textContent = opcao.text;
                    selectImpressora.appendChild(option);
                });
                
                // Adicionar impressoras encontradas
                impressoras.forEach(impressora => {
                    // Não adicionar duplicatas
                    if (!opcoesPadrao.find(op => op.value === impressora)) {
                        const option = document.createElement('option');
                        option.value = impressora;
                        option.textContent = impressora;
                        selectImpressora.appendChild(option);
                    }
                });
                
                console.log('Impressoras encontradas:', impressoras);
            } catch (error) {
                console.error('Erro ao buscar impressoras:', error);
                // Manter as opções padrão em caso de erro
            }
        }

        // Só descobrir impressoras quando o modal for aberto
        document.getElementById('btnImprimirQZ').addEventListener('click', function() {
            descobrirImpressoras();
            $('#impressoraModal').modal('show');
        });

        // Limpar conexão quando a página for descarregada (opcional)
        window.addEventListener('beforeunload', function() {
            if (qzConnected && qz.websocket.isActive()) {
                qz.websocket.disconnect();
            }
        });
    </script>
@endsection