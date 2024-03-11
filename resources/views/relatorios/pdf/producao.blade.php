<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Resultados</title>
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
            text-align: center
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px
        }
    </style>
</head>

<body>
    <h1 style="margin: 0 0 40px 0;text-align: center">Relatório de Produção</h1>
    <h3 style="text-align: center">Competência: {{ $data->format('d/m/Y') }}</h3>


    <table style=" margin:20px 0 0 0 ">
        <thead>
            <tr>
                <th>Horário</th>
                <th>Motorista</th>
                <th>Cliente</th>
                <th>Itens</th>
            </tr>
        </thead>
        <tbody>
            @if ($pedidos->isEmpty())
                <tr>
                    <td colspan="7">Sem Resultados</td>
                </tr>
            @else
                @php
                    $totalItens = [];
                @endphp
                @foreach ($pedidos as $pedido)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($pedido->dt_previsao)->format('H:i') }}</td>
                        <td>{{ $pedido->motorista->nome }}</td>
                        <td>{{ $pedido->cliente->name }}</td>
                        <td>
                            @foreach ($pedido->produtos as $produto)
                                {{ $produto->quantidade }} - {{ $produto->nome_produto }}
                                <br>
                                @php
                                    if (!isset($totalItens[$produto->nome_produto])) {
                                        $totalItens[$produto->nome_produto] = 0;
                                    }
                                    $totalItens[$produto->nome_produto] += $produto->quantidade;
                                @endphp
                            @endforeach

                        </td>
                    </tr>
                @endforeach

            @endif
        </tbody>
    </table>

    <table style="margin:20px 0 0 0 ">
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantidade Total</th>

            </tr>
        </thead>
        <tbody>
            @if ($pedidos->isEmpty())
            @else
                @foreach ($totalItens as $nome => $total)
                    <tr>
                        <td>{{ $nome }}</td>
                        <td>{{ $total }}</td>

                    </tr>
                @endforeach

            @endif
        </tbody>
    </table>
</body>

</html>
