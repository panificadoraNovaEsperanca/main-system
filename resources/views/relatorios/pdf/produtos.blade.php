<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Produtos</title>
    <style>
        @page { size: A4 landscape; }
        body, html { margin: 0.5%; padding: 0; }
        td, th { border: 1px solid black; text-align: center }
        table { width: 100%; border-collapse: collapse; font-size: 12px }
        .categoria-row { background:#ddd; font-weight:bold; text-align:left; }
    </style>
</head>

<body>
    <h1 style="margin:0;text-align:center">Relatório de Produtos</h1>
    <h3 style="text-align:center">Intervalo: {{ $inicio->format('d/m/Y H:i') }} - {{ $fim->format('d/m/Y H:i') }}</h3>

    <table style="margin-top:20px">
        <thead>
            <tr>
                <th>Categoria</th>
                <th>Produto</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($porCategoria as $categoria => $items)
            @foreach ($items as $item)
                <tr>
                    @if ($loop->first)
                        <td class="categoria-row" rowspan="{{ count($items) }}">
                            {{ $categoria }}
                        </td>
                    @endif
                    <td>{{ $item->nome_produto }}</td>
                    <td>{{ $item->total }}</td>
                </tr>
            @endforeach
        @empty
            <tr><td colspan="3">Sem resultados</td></tr>
        @endforelse
        </tbody>
    </table>
</body>

</html>
