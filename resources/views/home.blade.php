@extends('layouts.app')
@section('content')
    @hasGroup('administrador')
        <!-- Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $totalPedidosMes }}</h3>
                        <p>Pedidos Este Mês</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $pedidosHoje }}</h3>
                        <p>Pedidos Hoje</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $totalClientes }}</h3>
                        <p>Clientes Cadastrados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $totalProdutos }}</h3>
                        <p>Produtos Cadastrados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3>R$ {{ number_format($valorEstimadoMes, 2, ',', '.') }}</h3>
                        <p>Valor Estimado Mês</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box bg-teal">
                    <div class="inner">
                        <h3>{{ $totalMotoristas }}</h3>
                        <p>Motoristas Ativos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-truck"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box bg-orange">
                    <div class="inner">
                        <h3>{{ $totalPedidosMes > 0 ? round($totalPedidosMes / Carbon\Carbon::now()->day, 1) : 0 }}</h3>
                        <p>Pedidos por Dia</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pedidos por Mês - {{ Carbon\Carbon::now()->year }}</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="pedidosAnualChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pedidos - Últimos 7 Dias</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="pedidosPorDiaChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Top 5 Clientes (Mês)</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="topClientesChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Produtos Mais Vendidos (Mês)</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="produtosMaisVendidosChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- NOVOS GRÁFICOS - Insumos -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Top 5 Insumos no Limite</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="insumosNoLimiteChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>

     
        </div>

        <script>
            $(document).ready(function() {
                // 1. Gráfico Anual de Pedidos
                const anualCtx = document.getElementById('pedidosAnualChart');
                
                new Chart(anualCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($labelsAnual) !!},
                        datasets: [{
                            label: 'Pedidos',
                            data: {!! json_encode($dataAnual) !!},
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Pedidos por Mês - {{ Carbon\Carbon::now()->year }}'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // 2. Gráfico de Pedidos por Dia (Últimos 7 dias)
                const pedidosDiaCtx = document.getElementById('pedidosPorDiaChart');
                const pedidosDiaData = {
                    labels: {!! json_encode($pedidosPorDia->pluck('data')->map(function($data) {
                        return \Carbon\Carbon::parse($data)->format('d/m');
                    })) !!},
                    datasets: [{
                        label: 'Pedidos',
                        data: {!! json_encode($pedidosPorDia->pluck('total')) !!},
                        backgroundColor: 'rgba(40, 167, 69, 0.5)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 2,
                        tension: 0.3
                    }]
                };
                new Chart(pedidosDiaCtx, {
                    type: 'line',
                    data: pedidosDiaData,
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // 3. Gráfico de Top Clientes
                const clientesCtx = document.getElementById('topClientesChart');
                const clientesData = {
                    labels: {!! json_encode($topClientes->pluck('name')) !!},
                    datasets: [{
                        label: 'Pedidos',
                        data: {!! json_encode($topClientes->pluck('total_pedidos')) !!},
                        backgroundColor: 'rgba(153, 102, 255, 0.5)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                };
                new Chart(clientesCtx, {
                    type: 'bar',
                    data: clientesData,
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        scales: {
                            x: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // 4. Gráfico de Produtos Mais Vendidos
                const produtosCtx = document.getElementById('produtosMaisVendidosChart');
                const produtosData = {
                    labels: {!! json_encode($produtosMaisVendidos->pluck('nome')) !!},
                    datasets: [{
                        label: 'Quantidade Vendida',
                        data: {!! json_encode($produtosMaisVendidos->pluck('total_vendido')) !!},
                        backgroundColor: 'rgba(255, 159, 64, 0.5)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }]
                };
                new Chart(produtosCtx, {
                    type: 'bar',
                    data: produtosData,
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // 5. NOVO: Gráfico de Insumos no Limite
                const insumosCtx = document.getElementById('insumosNoLimiteChart');
                const insumosData = {
                    labels: {!! json_encode($topInsumosNoLimite->pluck('nome')) !!},
                    datasets: [
                        {
                            label: 'Estoque Atual',
                            data: {!! json_encode($topInsumosNoLimite->pluck('quantidade_atual')) !!},
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Estoque Mínimo',
                            data: {!! json_encode($topInsumosNoLimite->pluck('quantidade_minima')) !!},
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }
                    ]
                };
                new Chart(insumosCtx, {
                    type: 'bar',
                    data: insumosData,
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

       
            });
        </script>

        <style>
            .bg-purple { background-color: #6f42c1 !important; color: white; }
            .bg-teal { background-color: #20c997 !important; color: white; }
            .bg-orange { background-color: #fd7e14 !important; color: white; }
        </style>
    @endhasGroup
@endsection