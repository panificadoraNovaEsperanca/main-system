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
    <h3 style="text-align: center">Competência: {{$dia}}</h3>
    <table>
        <caption>
            Informações do Motorista
        </caption>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Turno</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $motorista->nome }}</td>
                <td>{{ $motorista->turno }}</td>
            </tr>
        </tbody>
    </table>

    <table style="page-break-after: always; margin:20px 0 0 0 ">
        <thead>
            <tr>
                <th>N° Pedido</th>
                <th>Cliente</th>
                <th>Endereço</th>
                <th>Item - Quantidade </th>
                <th>Check Separação</th>
                <th>Check Motorista</th>
            </tr>
        </thead>
        <tbody>
            @if ($pedidos->isEmpty())
            <tr><td colspan="7">Sem Resultados</td></tr>
            @else
                @foreach ($pedidos as $pedido)
                    @php
                        $totalPedido = 0;
                    @endphp
                    <tr>
                        <td>{{ $pedido->id }}</td>
                        <td>{{ $pedido->cliente->name }}</td>
                        <td>{!! $pedido->cliente->endereco_completo !!}</td>
                        <td>
                            @foreach ($pedido->produtos as $produto)
                           

                                {{ $produto->nome_produto }}({{ $produto->produto->unidade }}) -
                                {{ $produto->quantidade }}<br>
                            @endforeach

                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach

            @endif
        </tbody>
    </table>
  
</body>

</html>
