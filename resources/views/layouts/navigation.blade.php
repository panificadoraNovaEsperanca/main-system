<!-- Sidebar -->
<div class="sidebar">

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link">
                    <i class="nav-icon fa-solid fa-house"></i>
                    <p>
                        {{ __('Página Principal') }}
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('cliente.index') }}" class="nav-link">
                    <i class="nav-icon fa-solid fa-users"></i>
                    <p>
                        Clientes
                    </p>

                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('produto.index') }}" class="nav-link">
                    <i class="nav-icon fa-solid fa-utensils"></i>
                    <p>
                        Produtos
                    </p>
                </a>
            </li>


            <li class="nav-item">
                <a href="{{ route('pedido.index') }}" class="nav-link">
                    <i class="nav-icon fa fa-cart-shopping"></i>
                    <p>
                        Pedidos
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('motorista.index') }}" class="nav-link">
                    <i class="nav-icon fa-solid fa-truck-fast"></i>
                    <p>
                        Motoristas
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pedido.atualiza') }}" class="nav-link">
                    <i class="nav-icon fa-solid fa-magnifying-glass"></i>
                    <p>
                        Baixa de Pedidos
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-chart-pie"></i>
                    <p>
                        Relatórios
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('motorista.relatorio.index') }}" class="nav-link">
                            <i class="nav-icon fa-solid fa-truck"></i>
                            <p>
                                Relatórios de motorista
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('cliente.relatorio.index') }}" class="nav-link">
                            <i class="nav-icon fa-solid fa-user-tie"></i>
                            <p>
                                Relatórios de Cliente
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('produto.relatorio.index') }}" class="nav-link">
                            <i class="nav-icon fa-solid fa-bread-slice"></i>
                            <p>
                                Relatórios de Produtos
                            </p>
                        </a>
                    </li>

                </ul>
            </li>



        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
