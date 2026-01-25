@extends('layouts.app')
@section('title', 'Status do Estoque')

@section('actions')
    <a href="{{ route('estoque.create') }}" class="btn btn-primary">
        Cadastrar movimentação
    </a>
@endsection

@section('content')
    <form class="mr-2 d-flex row justify-content-between" id="formSearch" action="{{ route('estoque.status') }}" method="GET">

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
                {{ $insumos->appends(['paginacao' => $_GET['paginacao'] ?? 10, 'search' => $_GET['search'] ?? '']) }}
            </div>
            <div class=" input-group mb-3 d-flex">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                </div>
                <input value="{{ $_GET['search'] ?? '' }}" type="text" id="search" name="search" class="form-control"
                    placeholder="Buscar insumo..." aria-label="" aria-describedby="basic-addon1">
                <a href="{{ route('estoque.status') }}" class="btn btn-primary ">Limpar busca</a>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        @if (!$insumos->isEmpty())
            <table id="insumosTable" class="table shadow rounded table-striped ">
                <thead class="bg-primary ">
                    <tr>
                        <th>Insumo</th>
                        <th>Unidade de Medida</th>
                        <th>Quantidade Mínima</th>
                        <th>Quantidade Atual</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach ($insumos as $insumo)
                        <tr>
                            <td>{{ $insumo->nome }}</td>
                            <td>{{ $insumo->unidade_medida }}</td>
                            <td>{{ $insumo->quantidade_minima ?? '-' }}</td>
                            <td>
                                <strong>{{ $insumo->quantidade_atual ?? '0' }}</strong>
                            </td>
                            <td>
                                @if($insumo->quantidade_atual > $insumo->quantidade_minima)
                                    <span class="badge badge-success">Normal</span>
                                @elseif($insumo->quantidade_atual == $insumo->quantidade_minima)
                                    <span class="badge badge-warning">Atenção</span>
                                @else
                                    <span class="badge badge-danger">Necessário Reposição</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
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

