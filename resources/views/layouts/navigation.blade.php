<!-- Sidebar -->
<div class="sidebar">

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            
            <!-- Página Principal -->
            @hasGroup('administrador')
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="nav-icon fa-solid fa-house"></i>
                        <p>{{ __('Página Principal') }}</p>
                    </a>
                </li>
            @endhasGroup

            <!-- Cadastros -->
            @hasGroup('administrador')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa-solid fa-folder"></i>
                        <p>
                            Cadastros
                            <i class="right fa-solid fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('categoria.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-tags"></i>
                                <p>Categorias de Produto</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cliente.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-users"></i>
                                <p>Clientes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('motorista.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-id-card"></i>
                                <p>Motoristas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('produto.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-boxes-stacked"></i>
                                <p>Produtos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('setor.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-building"></i>
                                <p>Setores</p>
                            </a>
                        </li>
                    </ul>
                </li>
            @endhasGroup

            <!-- Almoxarifado -->
            @hasGroup('administrador|almoxarifado')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa-solid fa-layer-group"></i>
                        <p>
                            Almoxarifado
                            <i class="right fa-solid fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        @hasGroup('administrador|almoxarifado')
                            <li class="nav-item">
                                <a href="{{ route('estoque.index') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-warehouse"></i>
                                    <p>Estoque</p>
                                </a>
                            </li>
                        @endhasGroup
                        @hasGroup('administrador')
                            <li class="nav-item">
                                <a href="{{ route('insumo.index') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-vial"></i>
                                    <p>Insumos</p>
                                </a>
                            </li>
                        @endhasGroup
                    </ul>
                </li>
            @endhasGroup

            <!-- Operações -->
            @hasGroup('administrador|motorista')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa-solid fa-clipboard-list"></i>
                        <p>
                            Operações
                            <i class="right fa-solid fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        @hasGroup('administrador')
                            <li class="nav-item">
                                <a href="{{ route('pedido.index') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-clipboard-list"></i>
                                    <p>Pedidos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('pedido.atualiza') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-arrow-down-short-wide"></i>
                                    <p>Baixa de Pedidos</p>
                                </a>
                            </li>
                        @endhasGroup
                        @hasGroup('administrador|motorista')
                            <li class="nav-item">
                                <a href="{{ route('motorista.entrega.index') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-truck-ramp-box"></i>
                                    <p>Entrega de Pedidos</p>
                                </a>
                            </li>
                        @endhasGroup
                    </ul>
                </li>
            @endhasGroup

            <!-- Produção -->
            @hasGroup('administrador|producao')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa-solid fa-gears"></i>
                        <p>
                            Produção
                            <i class="right fa-solid fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('producaoBaixa.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-clipboard-check"></i>
                                <p>Baixa de Produção</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('producao.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-gears"></i>
                                <p>Cadastro de Produção</p>
                            </a>
                        </li>
                    </ul>
                </li>
            @endhasGroup

            <!-- Relatórios -->
            @hasGroup('administrador')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa-solid fa-folder-open"></i>
                        <p>
                            Relatórios
                            <i class="right fa-solid fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('cliente.relatorio.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-user-group"></i>
                                <p>Relatórios de Cliente</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('motorista.relatorio.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-truck"></i>
                                <p>Relatórios de Motorista</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('producao.relatorio') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-industry"></i>
                                <p>Relatórios de Produção</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('produto.relatorio.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-cube"></i>
                                <p>Relatórios de Produtos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('produto.relatorio.cliente.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-users"></i>
                                <p>Produtos por Cliente</p>
                            </a>
                        </li>
                    </ul>
                </li>
            @endhasGroup

            <!-- Configurações -->
            @hasGroup('administrador')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa-solid fa-sliders"></i>
                        <p>
                            Configurações
                            <i class="right fa-solid fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('grupo.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-user-shield"></i>
                                <p>Grupos de Permissão</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('permissao.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-key"></i>
                                <p>Permissões</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-user-gear"></i>
                                <p>Usuários</p>
                            </a>
                        </li>
                    </ul>
                </li>
            @endhasGroup

        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
