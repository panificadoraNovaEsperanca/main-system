@extends('layouts.app')
@section('title', 'Histórico de Transações')

@section('actions')
    <a href="{{ route('estoque.create') }}" class="btn btn-primary">
        Cadastrar movimentação
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
            <table id="estoquesTable" class="table shadow rounded table-striped ">
                <thead class="bg-primary ">
                    <tr>
                        <th>Id</th>
                        <th>Insumo</th>
                        <th>Tipo</th>
                        <th>Quantidade</th>
                        <th>Descrição</th>
                        <th>Usuário Operador</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach ($estoques as $estoque)
                        <tr>
                            <td>{{ $estoque->id }}</td>
                            <td>{{ $estoque->insumo->nome }}</td>
                            <td>
                                <span class="badge {{ $estoque->tipo == 'entrada' ? 'badge-success' : 'badge-danger' }}">
                                    {{ $estoque->tipo == 'entrada' ? 'ENTRADA' : 'SAÍDA' }}
                                </span>
                            </td>
                            <td>{{ $estoque->valor }}</td>
                            <td>{{ $estoque->descricao ?? '-' }}</td>
                            <td>{{ $estoque->operador->name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($estoque->created_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <x-not-found />
        @endif
    </div>

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
