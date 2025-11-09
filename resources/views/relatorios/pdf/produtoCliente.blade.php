<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Produtos por Cliente</title>
    <style>
        @page {
            size: A4 landscape;
        }

        body,
        html {
            margin: 0.5%;
            padding: 0;
        }

        td,
        th {
            border: 1px solid black;
            text-align: center;
            padding: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px
        }

        .header-row {
            background: #ddd;
            font-weight: bold;
            text-align: left;
        }
    </style>
</head>

<body>
    <h1 style="margin:0;text-align:center">Relatório de Produtos por Cliente</h1>
    <h3 style="text-align:center">Produto: {{ $produto->nome }}</h3>
    <h3 style="text-align:center">Intervalo: {{ $inicio->format('d/m/Y H:i') }} - {{ $fim->format('d/m/Y H:i') }}</h3>

    <table style="margin-top:20px">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>CNPJ</th>
                <th>Quantidade Total</th>
                <th>Valor Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($clientes as $cliente)
                <tr>
                    <td style="text-align:left">{{ $cliente->nome_cliente }}</td>
                    <td>{{ $cliente->cnpj ?? 'N/A' }}</td>
                    <td>{{ number_format($cliente->total_quantidade, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($cliente->total_valor, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Sem resultados</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>

