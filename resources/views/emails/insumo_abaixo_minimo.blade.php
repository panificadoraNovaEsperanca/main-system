<p>O seguinte insumo está abaixo da quantidade mínima:</p>

<p><strong>{{ $insumo->nome }}</strong></p>
<p>Quantidade atual: {{ number_format($insumo->quantidade_atual,4,',','.') }}</p>
<p>Quantidade mínima: {{ number_format($insumo->quantidade_minima,4,',','.') }}</p>
