@extends('layouts.app')
@section('title', 'Insumos')

@section('actions')
    <a href="{{ route('insumo.create') }}" class="btn btn-primary">
        Cadastrar insumo
    </a>
@endsection


@section('content')
    <form class="mr-2 d-flex row justify-content-between" id="formSearch" action="{{ route('insumo.index') }}" method="GET">


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
                {{ $insumos->appends(['paginacao' => $_GET['paginacao'] ?? 10]) }}

            </div>
            <div class=" input-group mb-3 d-flex">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                </div>
                <input value="{{ $_GET['search'] ?? '' }}" type="text" id="search" name="search" class="form-control"
                    placeholder="" aria-label="" aria-describedby="basic-addon1">
                <a href="{{ route('insumo.index') }}" class="btn btn-primary ">Limpar busca</a>

            </div>
        </div>


    </form>
    <div class="table-responsive">

        @if (!$insumos->isEmpty())
            <table id="insumosTable" class="table shadow rounded table-striped ">
                <thead class="bg-primary ">
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Unidade</th>
                        <th>Quantidade Mínima</th>
                        <th>Quantidade Atual</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach ($insumos as $insumo)
                        <tr @if ($insumo->deleted_at != null) style="background-color:#ff8e8e" @endif>

                            <td>{{ $insumo->id }}
                            </td>

                            <td>{{ $insumo->nome }}</td>

                            <td style="text-overflow: ellipsis">{{ $insumo->unidade_medida }}</td>
                            <td>{{ $insumo->quantidade_minima }}</td>
                            <td>{{ $insumo->quantidade_atual ?? 'Vazio' }}</td>


                            <td class="d-flex  justify-content-around">

                                @if ($insumo->deleted_at == null)
                                    <a href="{{ route('insumo.edit', $insumo->id) }}" type="button"
                                        class="btn btn-warning mr-1 editModal"><i class="fas fa-edit"></i></a>
                                @endif
                                <form method="POST"
                                    action="{{ route($insumo->deleted_at == null ? 'insumo.destroy' : 'insumo.ativar', $insumo->id) }}"
                                    enctype="multipart/form-data">
                                    @if ($insumo->deleted_at == null)
                                        @method('DELETE')
                                    @else
                                        @method('PUT')
                                    @endif
                                    @csrf
                                    <button type="submit"
                                        class="btn {{ $insumo->deleted_at == null ? 'btn-danger' : 'btn-success' }}"><i
                                            class="fa fa-power-off"></i></button>

                                </form>



                            </td>
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
