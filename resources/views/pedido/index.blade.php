@extends('layouts.app')
@section('title', 'Pedidos')

@section('actions')
    <a href="{{ route('pedido.create') }}" class="btn btn-primary">
        Cadastrar pedido
    </a>
@endsection


@section('content')


    <form class="mr-2 d-flex flex-column" id="formSearch" action="{{ route('pedido.index') }}" method="GET">
        <div class="row">
            <div class="col-3">

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                    </div>
                    <input value="{{ $_GET['search'] ?? '' }}" type="text" id="search" name="search"
                        class="form-control" placeholder="Cliente ou Motorista" aria-label=""
                        aria-describedby="basic-addon1">
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input value="{{ $_GET['dataHora'] ?? '' }}" placeholder="Data de entrega" type="text"
                            class="form-control float-right" name="dataHora" id="dataHora">
                    </div>

                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <div class="input-group">
                        <select class="custom-select" id="status" name="status">
                            <option value="-1" >Selecione uma opção</option>

                            <option {{ isset($_GET['status']) && $_GET['status'] == 'AGENDADO' ? 'selected' : '' }}
                                value="AGENDADO">Agendado</option>
                            <option {{ isset($_GET['status']) && $_GET['status'] == 'ENTREGUE' ? 'selected' : '' }}
                                value="ENTREGUE">Entregue</option>
                            <option {{ isset($_GET['status']) && $_GET['status'] == 'CANCELADO' ? 'selected' : '' }}
                                value="CANCELADO">Cancelado</option>

                        </select>
                    </div>

                </div>
            </div>
            <div class="col-1">
                <a href="{{ route('pedido.index') }}" class="btn btn-primary">Limpar busca</a>

            </div>
            <div class="col-1">
            </div>
            <div class="col-1">

                <select id="paginacao" name="paginacao" class="custom-select mr-2" style="min-width: 80px"
                    id="inputGroupSelect01">
                  
                    <option value="30" {{ isset($_GET['paginacao']) && $_GET['paginacao'] == '30' ? 'selected' : '' }}>
                        30
                    </option>
                    <option value="50" {{ isset($_GET['paginacao']) && $_GET['paginacao'] == '50' ? 'selected' : '' }}>
                        50
                    </option>
                    <option value="100" {{ isset($_GET['paginacao']) && $_GET['paginacao'] == '100' ? 'selected' : '' }}>
                        100
                    </option>


                </select>
            </div>
            {{ $pedidos->appends(['paginacao' => $_GET['paginacao'] ?? 10]) }}
        </div>
        <div class="d-flex flex-row input-group row justify-content-end">
            <div class="  ">

                <div class="col-10">

                </div>

            </div>
        </div>
    </form>
    @if (!$pedidos->isEmpty())
        <table id="pedidoTable" class="table shadow rounded table-striped table-hover">
            <thead class="bg-primary ">
                <tr>
                    <th>Id</th>
                    <th>Cliente</th>
                    <th>Motorista</th>
                    <th>Status</th>
                    <th>Data de entrega</th>
                    <th class="d-flex justify-content-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $pedido)
                    <tr @if ($pedido->deleted_at != null) style="background-color:#ff8e8e" @endif>
                        <td>{{ $pedido->id }}</td>
                        <td>{{ $pedido->cliente->name }}</td>
                        <td>{{ $pedido->motorista->nome }}</td>
                        <td>{{ $pedido->status }}</td>
                        <td>{{ \Carbon\Carbon::parse($pedido->dt_previsao)->format('d/m/Y H:i') }}</td>

                        <td class="d-flex  justify-content-around">
                            <button data-id="{{ $pedido->id }}" type="button" class="btn btn-primary infoPedido">
                                <i class="fas fa-info"></i>
                            </button>
                            @if ($pedido->deleted_at == null)
                                <a href="{{ route('pedido.edit', $pedido->id) }}" type="button"
                                    class="btn btn-warning mr-1 editModal"><i class="fas fa-edit"></i></a>
                            @endif
                            <form method="POST"
                                action="{{ route($pedido->deleted_at == null ? 'pedido.destroy' : 'pedido.ativar', $pedido->id) }}"
                                enctype="multipart/form-data">
                                @if ($pedido->deleted_at == null)
                                    @method('DELETE')
                                @else
                                    @method('PUT')
                                @endif
                                @csrf
                                @if ($pedido->status != 'ENTREGUE')
                                    <button type="submit"
                                        class="btn {{ $pedido->deleted_at == null ? 'btn-danger' : 'btn-success' }}"><i
                                            class="fa fa-trash"></i></button>
                                @endif
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    @else
        <x-not-found />

    @endif


    <div class="modal fade" id="produtoModal" tabindex="-1" aria-labelledby="produtoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="produtoModalLabel">
                        Pedido n° <b><span id="idPedido"></span></b>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="">
                        <div class="row">
                            <div class="col-6">
                                <label for="exampleInputEmail1" class="form-label">Cliente</label>
                                <input disabled value="" class="form-control" id="nomeCliente">
                            </div>
                            <div class="col-6">
                                <label for="exampleInputEmail1" class="form-label">Motorista</label>
                                <input disabled value="" class="form-control" id="nomeMotorista">
                            </div>
                            <div class="col-6">
                                <label for="exampleInputEmail1" class="form-label">Data de Entrega</label>
                                <input disabled value="" class="form-control" id="dataEntrega">
                            </div>
                            <div class="col-6">
                                <label for="exampleInputEmail1" class="form-label">Status</label>
                                <input disabled value="" class="form-control" id="status">
                            </div>
                            <div class="col-12 mt-4">
                                <table class="table table-bordered" id="tableProdutos">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Quantidade</th>
                                            <th>Preço unitário de venda</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableProdutosBody">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@push('scripts')
    <script>
        $('#dataHora').datetimepicker({
            i18n: {
                de: {
                    months: [
                        'Janeiro',
                        'Fevereiro',
                        'Março',
                        'Abril',
                        'Maio',
                        'Junho',
                        'Julho',
                        'Agosto',
                        'Setembro',
                        'Outubro',
                        'Novembro',
                        'Dezembro'
                    ],
                    dayOfWeek: [
                        'Dom',
                        'Seg',
                        'Ter',
                        'Qua',
                        'Qui',
                        'Sex',
                        'Sáb'
                    ]
                }
            },
            onSelect: function(dateText) {
                console.log(dateText)
                document.getElementById('formSearch').submit()

            },
            format: 'd/m/Y',
            lang: 'pt'
        });
        document.getElementById('search').addEventListener('change', function() {
            document.getElementById('formSearch').submit()
        })
        $("#dataHora").on("change.datetimepicker", ({
            date,
            oldDate
        }) => {
            document.getElementById('formSearch').submit()
            return ''
        })
        document.getElementById('dataHora').addEventListener('change', function() {})
        document.getElementById('status').addEventListener('change', function() {
            document.getElementById('formSearch').submit()
        })
        document.getElementById('paginacao').addEventListener('change', function() {
            document.getElementById('formSearch').submit()
        })

        $('.infoPedido').on('click', function() {
            console.log(this)
            fetch(`/pedido/${this.dataset.id}`).then(async (response) => {
                let result = await response.json();

                $('#tableProdutosBody').empty();
                $('#idPedido').text(result.data.id);
                $('#nomeCliente').val(result.data.cliente.name);
                $('#nomeMotorista').val(result.data.motorista.nome);
                $('#dataEntrega').val(result.data.dt_previsao_formatted);
                $('#status').val(result.data.status);
                let html = ``;
                for (let produto of result.data.produtos) {
                    html += `<tr>
                        <td>${produto.nome_produto}</td>
                        <td>${produto.quantidade}</td>
                        <td>${produto.preco}</td>
                        </tr>`
                }
                console.log(html)
                $('#tableProdutosBody').append(html)
                $('#produtoModal').modal('show')
            })

        })
    </script>
@endpush
