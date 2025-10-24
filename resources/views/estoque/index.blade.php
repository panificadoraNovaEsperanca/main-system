@extends('layouts.app')
@section('title', 'Estoque')

@section('actions')
    <a href="{{ route('estoque.create') }}" class="btn btn-primary">
        Cadastrar estoque
    </a>
@endsection


@section('content')
    <form class="mr-2 d-flex row justify-content-between" id="formSearch" action="{{ route('estoque.index') }}" method="GET">


        <div class="d-flex col-md-12 col-sm-12 col-6">
            <div class="d-flex  mb-3">
                <div class="input-group  col-2">
                    <select id="paginacao" name="paginacao" class="custom-select mr-2" style="min-width: 80px"
                        id="inputGroupSelect01">
                        <option value="10"
                            {{ isset($_GET['paginacao']) && $_GET['paginacao'] == '10' ? 'selected' : '' }}>
                            10
                        </option>
                        <option value="20"
                            {{ isset($_GET['paginacao']) && $_GET['paginacao'] == '20' ? 'selected' : '' }}>
                            20
                        </option>
                        <option value="30"
                            {{ isset($_GET['paginacao']) && $_GET['paginacao'] == '30' ? 'selected' : '' }}>
                            30
                        </option>


                    </select>
                </div>
                {{ $estoques->appends(['paginacao' => $_GET['paginacao'] ?? 10]) }}

            </div>
            <div class=" input-group mb-3 d-flex">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                </div>
                <input value="{{ $_GET['search'] ?? '' }}" type="text" id="search" name="search" class="form-control"
                    placeholder="" aria-label="" aria-describedby="basic-addon1">
                <a href="{{ route('estoque.index') }}" class="btn btn-primary ">Limpar busca</a>

            </div>
        </div>


    </form>



    <div class="table-responsive">

        @if (!$estoques->isEmpty())
            <h3>Histórico de Transações</h3>
            <table id="estoquesTable" class="table shadow rounded table-striped table-hover">
                <thead class="bg-primary ">
                    <tr>
                        <th>Id</th>
                        <th>Insumo</th>
                        <th>Tipo</th>
                        <th>Quantidade</th>
                        <th>Usuário Operador</th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach ($estoques as $estoque)
                        <tr>

                            <td>{{ $estoque->id }}</td>
                            <td>{{ $estoque->insumo->nome }}</td>
                            <td style="text-overflow: ellipsis">{{ $estoque->tipo == 'entrada' ? 'ENTRADA' : 'SAÍDA' }}
                            </td>
                            <td>{{ $estoque->valor }}</td>
                            <td>{{ $estoque->operador->name ?? 'Vazio' }}</td>
                        </tr>
                    @endforeach
                <tfoot>


                </tfoot>
                </tbody>
            </table>
        @else
            <x-not-found />
        @endif
    </div>
    <div class="table-responsive mt-3">

        @if (!$insumos->isEmpty())
            <h3>Status de Estoque</h3>
            {{ $insumos->appends(['paginacao' => $_GET['paginacao'] ?? 10]) }}

            <table id="estoquesTable" class="table shadow rounded table-striped table-hover">
                <thead class="bg-primary ">
                    <tr>
                        <th>Insumo</th>
                        <th>Quantidade Mínima</th>
                        <th>Quantidade Atual</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach ($insumos as $insumo)
                        <tr>
                            <td>{{ $insumo->nome }}</td>
                            <td>{{ $insumo->quantidade_minima }}</td>
                            <td>{{ $insumo->quantidade_atual ?? 'Vazio' }}</td>
                            <td>{{
                                $insumo->quantidade_atual > $insumo->quantidade_minima ? 'Normal' : 'Necessário reposição'
                                }}</td>
                        </tr>
                    @endforeach
                <tfoot>


                </tfoot>
                </tbody>
            </table>
        @else
            <x-not-found />
        @endif
    </div>
    @include('insumo.modalInfo')

@endsection

@push('scripts')
    <script>
        document.getElementById('search').addEventListener('change', function() {
            document.getElementById('formSearch').submit()
        })
        document.getElementById('paginacao').addEventListener('change', function() {
            document.getElementById('formSearch').submit()
        })
    </script>
@endpush
