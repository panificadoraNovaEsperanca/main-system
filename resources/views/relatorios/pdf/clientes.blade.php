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
    <h1 style="margin: 0 0 40px 0;text-align: center">Relatório de Entregas</h1>
    <h3 style="text-align: center">Intervalo: {{ $inicio->format('d/m/Y H:i') }} - {{ $fim->format('d/m/Y H:i') }}</h3>

    <table>
        <caption>
            Informações do Cliente
        </caption>
        <thead>
            <tr>
                <th>Nome</th>
                <th>CNPJ</th>
                <th>Endereço</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $cliente->name }}</td>
                <td>{{ $cliente->cnpj }}</td>
                <td>{!! $cliente->endereco_completo !!}</td>
            </tr>
        </tbody>
    </table>

    <table style="page-break-after: always; ">
        <caption>
            Pedidos
        </caption>
        <thead>
            <tr>
                <th>Pedido</th>
                <th>Horario</th>
                <th>Item - Quantidade - Valor.un</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @if ($pedidos->isEmpty())
                <tr>
                    <td colspan="5">Sem Resultados</td>
                </tr>
            @else
                @foreach ($pedidos as $pedido)
                    @php
                        $totalPedido = 0;
                    @endphp
                    <tr>
                        <td>{{ $pedido->id }}</td>
                        <td>{{ $pedido->dt_previsao_formatted }}</td>
                        <td>
                            @foreach ($pedido->produtos as $produto)
                                @php
                                    $totalPedido += $produto->preco * $produto->quantidade;
                                @endphp

                                {{ $produto->nome_produto }}({{ $produto->produto->unidade }}) -
                                {{ $produto->quantidade }} - R$ {{ $produto->preco }}<br>
                            @endforeach

                        </td>
                        <td>{{ $totalPedido }}</td>
                        <td>{{ $pedido->status }}</td>
                    </tr>
                @endforeach

            @endif
        </tbody>
    </table>
    <table style="page-break-after: always; ">
        <caption>
            Produtos
        </caption>
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantidade</th>
                <th>Unidade</th>
                <th>R$ Valor.un</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalCliente = 0;
                $totalQuantidade = 0;
            @endphp
            @foreach ($pedidos as $pedido)
                @foreach ($pedido->produtos as $produto)
                    @php
                        $totalProduto = $produto->preco * $produto->quantidade;
                        $totalCliente += $totalProduto;
                        $totalQuantidade += $produto->quantidade;
                    @endphp
                    <tr>
                        <td>{{ $produto->nome_produto }}</td>
                        <td>{{ $produto->quantidade }}</td>
                        <td>{{ $produto->produto->unidade }}</td>
                        <td>{{ $produto->preco }}</td>
                        <td>{{ $totalProduto }}</td>
                    </tr>
                @endforeach
            @endforeach
            <tr>
                <td><b>TOTAL</b></td>
                <td>{{$totalQuantidade}}</td>
                <td></td>
                <td></td>
                <td>{{$totalCliente}}</td>
            </tr>
        </tbody>

    </table>


</body>

</html>
