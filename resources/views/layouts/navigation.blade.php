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
                    <i class=" nav-icon fa-solid fa-list"></i>
                    <p>
                        Clientes
                    </p>

                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('produto.index') }}" class="nav-link">
                    <i class="nav-icon fa-solid fa-copyright"></i>
                    <p>
                        Produtos
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('fornecedor.index') }}" class="nav-link">
                    <i class="nav-icon fa-solid fa-business-time"></i>
                    <p>
                        Rotas
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('produto.index') }}" class="nav-link">
                    <i class="nav-icon fa-solid fa-barcode"></i>
                    <p>
                        Pedidos
                    </p>
                </a>
            </li>
      
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
