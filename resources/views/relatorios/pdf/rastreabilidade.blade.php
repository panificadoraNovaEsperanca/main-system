<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Rastreabilidade</title>
    <style>
        @page {
            size: A4;
            margin: 2cm 1.5cm;
        }

        * {
            box-sizing: border-box;
        }

        body,
        html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 11px;
        }
        
        .container {
            padding: 15px 20px;
            max-width: 100%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0 0 10px 0;
            font-size: 20px;
            font-weight: bold;
        }

        .header h3 {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        .produto-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .produto-header {
            background-color: #f0f0f0;
            padding: 10px;
            border-left: 4px solid #007bff;
            margin-bottom: 15px;
        }

        .produto-header h3 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
        }

        .produto-info {
            margin-bottom: 10px;
            padding: 5px 0;
        }

        .produto-info strong {
            display: inline-block;
            width: 150px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        .status-nao-produzido {
            color: #dc3545;
            font-weight: bold;
        }

        .status-produzido {
            color: #28a745;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
    <div class="header">
        <h1>Relatório de Rastreabilidade</h1>
        <h3>Data do Relatório: {{ $data->format('d/m/Y') }}</h3>
        <h3>Gerado em: {{ $dataGeracao->format('d/m/Y H:i:s') }}</h3>
    </div>

    @foreach ($lotesPorProduto as $produtoRastreavelId => $lotes)
        @php
            $primeiroLote = $lotes->first();
            $produto = $primeiroLote->produtoRastreavel->produto;
        @endphp
        <div class="produto-section">
            <div class="produto-header">
                <h3>{{ $produto->nome }}</h3>
            </div>

            @foreach ($lotes as $lote)
                <div class="produto-info">
                    <strong>Número do Lote:</strong> {{ $lote->lote }}<br>
                    <strong>Data de Produção:</strong> {{ $lote->data_producao->format('d/m/Y') }}<br>
                    @if ($lote->responsavel)
                        <strong>Responsável:</strong> {{ $lote->responsavel }}<br>
                    @endif
                    <strong>Status:</strong>
                    @if ($lote->nao_produzido)
                        <span class="status-nao-produzido">NÃO PRODUZIDO NO DIA</span>
                    @else
                        <span class="status-produzido">PRODUZIDO</span>
                    @endif
                </div>

                @if (!$lote->nao_produzido && $lote->ingredientes->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>Ingrediente</th>
                                <th>Marca</th>
                                <th>Lote do Ingrediente</th>
                                <th>Validade Original</th>
                                <th>Data de Abertura</th>
                                <th>Validade Após Aberto</th>
                                <th>Característica Sensorial</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lote->ingredientes as $ingrediente)
                                <tr>
                                    <td>{{ $ingrediente->insumo->nome }}</td>
                                    <td>{{ $ingrediente->marca ?? '-' }}</td>
                                    <td>{{ $ingrediente->lote_ingrediente ?? '-' }}</td>
                                    <td>{{ $ingrediente->validade_original ? $ingrediente->validade_original->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $ingrediente->data_abertura ? $ingrediente->data_abertura->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $ingrediente->validade_apos_aberto ? $ingrediente->validade_apos_aberto->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $ingrediente->caracteristica_sensorial ?? 'Conforme' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @elseif ($lote->nao_produzido)
                    <p style="font-style: italic; color: #666; padding: 10px;">
                        Produto não foi produzido neste dia.
                    </p>
                @else
                    <p style="font-style: italic; color: #666; padding: 10px;">
                        Nenhum ingrediente registrado para este lote.
                    </p>
                @endif

                @if (!$loop->last)
                    <hr style="margin: 20px 0; border: 1px dashed #ccc;">
                @endif
            @endforeach
        </div>

        @if (!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach

    @if ($lotesPorProduto->isEmpty())
        <div class="produto-section">
            <p style="text-align: center; font-style: italic; color: #666; padding: 20px;">
                Nenhum registro de rastreabilidade encontrado para a data selecionada.
            </p>
        </div>
    @endif

    <div class="footer">
        <p>Relatório gerado automaticamente pelo Sistema de Rastreabilidade</p>
        <p>Sistema Panificadora Nova Esperança</p>
    </div>
    </div>
</body>

</html>
