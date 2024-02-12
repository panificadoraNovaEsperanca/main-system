@extends('layouts.app')
@section('title', isset($produto) ?: 'Baixa de pedidos')


@section('content')
    <form>

        <div class="row">
            <div class="col-5 ">
                <div class="form-group w-100">
                    <label for="exampleInputEmail1" class="form-label">Código do pedido</label>
                    <input type="number" value="" name="lote_id" class="form-control" id="codigoPedido">
                    @error('lote_id')
                        <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                    @enderror
                </div>
                <div class="form-group w-100">
                    <label>Status</label>
                    <div class="input-group">
                        <select class="custom-select" name="status" id="statusRequest">
                            <option hidden>Selecione uma opção</option>
                            <option {{ isset($pedido) && $pedido->status == 'AGENDADO' ? 'selected' : '' }}
                                value="AGENDADO">Agendado</option>
                            <option {{ isset($pedido) && $pedido->status == 'A CAMINHO' ? 'selected' : '' }}
                                value="A CAMINHO">A Caminho</option>
                            <option {{ isset($pedido) && $pedido->status == 'ENTREGUE' ? 'selected' : '' }}
                                value="ENTREGUE">Entregue</option>
                            <option {{ isset($pedido) && $pedido->status == 'CANCELADO' ? 'selected' : '' }}
                                value="CANCELADO">Cancelado</option>

                        </select>
                    </div>
                    @error('status')
                        <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
                    @enderror
                </div>
                <button id="submitButton" type="button" class="btn btn-primary mt-3">Atualizar pedido</button>

            </div>

            <div class="card  card-primary  ml-3 shadow-lg" id="infoLote" style="height: 20%;min-width:55%">
                <div class="card-header ">
                    <h3 class="card-title">Informações do Pedido</h3>
                    <div class="card-tools">

                    </div>

                </div>

                <div class="card-body">
                    <div> Cliente: <strong><span id="clienteNome"> </span></strong></div>
                    <div> Motorista : <strong><span id="motoristaNome"> </span></strong></div>
                    <div> Status Atual: <strong><span id="statusAtual"> </span></strong></div>
                    <div> Previsão de entrega: <strong><span id="previsaoEntrega"> </span></strong></div>
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
            </div>
        </div>

    </form>
    @if (!$pedidos->isEmpty())

        <div class="mt-5">
            <h3>Pedidos em aberto</h3>

            <form class="mr-2 d-flex justify-content-between" id="formSearch" action="{{ route('pedido.atualiza') }}"
                method="GET">
                <div class="d-flex ">

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                        </div>
                        <input value="{{ $_GET['search'] ?? '' }}" type="text" id="search" name="search"
                            class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon1">
                        <a href="{{ route('pedido.index') }}" class="btn btn-primary">Limpar busca</a>

                    </div>
                </div>
                <div class="d-flex">
                    <div class="input-group  ">
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
                        {{ $pedidos->appends(['paginacao' => $_GET['paginacao'] ?? 10]) }}

                    </div>
                </div>
            </form>
            <table id="pedidoTable" class="table shadow rounded table-striped table-hover">


                <thead class="bg-primary ">
                    <tr>
                        <th>Id</th>
                        <th>Cliente</th>
                        <th>Motorista</th>
                        <th>Data de entrega</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pedidos as $pedido)
                        <tr @if ($pedido->deleted_at != null) style="background-color:#ff8e8e" @endif>
                            <td>{{ $pedido->id }}</td>
                            <td>{{ $pedido->cliente->name }}</td>
                            <td>{{ $pedido->motorista->nome }}</td>
                            <td>{{ \Carbon\Carbon::parse($pedido->dt_previsao)->format('d/m/Y H:i') }}</td>


                        </tr>
                    @endforeach
                </tbody>

            </table>
        @else
            <x-not-found />

    @endif
    </div>
    <script>
        let products = [];
        document.getElementById('codigoPedido').addEventListener('change', async function() {
            showLoading();
            await fetch(`/getPedidoBaixa/${this.value}`, {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    "Content-Type": "application/json",

                }
            }).then(async (response) => {
                let result = await response.json();
                if (response.status == 400) {
                    Toast.fire({
                        heightAuto: true,
                        icon: 'error',
                        title: result.message
                    });
                } else {
                    if (result.data != null || result.success) {
                        $('#clienteNome').text(result.data.cliente.name)
                        $('#motoristaNome').text(result.data.motorista.nome)
                        $('#statusAtual').text(result.data.status)
                        $('#previsaoEntrega').text(result.data.dt_previsao_formatted)
                        $('#tableProdutosBody').empty();

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

                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: result.message
                        });
                    }



                }
            })
            hideLoading()

        })
        document.getElementById('submitButton').addEventListener('click', async function() {
            let id = $('#codigoPedido').val()
            console.log(id, $('#statusRequest').val())
            if (id != '' && $('#statusRequest').val() != '')
                await fetch(`/atualizaPedido/${id}`, {
                    method: "POST",
                    body: JSON.stringify({
                        status: $('#statusRequest').val()
                    }),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        "Content-Type": "application/json",

                    }
                }).then(async (response) => {
                    let result = await response.json();
                    if (result.success) {
                        $('#clienteNome').text('')
                        $('#motoristaNome').text('')
                        $('#statusAtual').text('')
                        $('#previsaoEntrega').text('')
                        $('#tableProdutosBody').empty();
                        $('#codigoPedido').val('')
                        $('#statusRequest').val('')
                    }
                    Toast.fire({
                        icon: result.success ? 'success' : 'error',
                        title: result.message
                    });


                })
        })
    </script>
@endsection
