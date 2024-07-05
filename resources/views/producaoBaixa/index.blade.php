@extends('layouts.app')
@section('title', 'Baixa de Produção')

@section('actions')

@endsection


@section('content')


    {{-- <form class="mr-2 d-flex flex-column" id="formSearch" action="{{ route('pedido.index') }}" method="GET">
    <div class="row">
      <div class="col-md-3 col-sm-12">

        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
          </div>
          <input autocomplete="off" value="{{ $_GET['motorista'] ?? '' }}" type="text" id="motorista" name="motorista"
            class="form-control" placeholder=" Motorista" aria-label="" aria-describedby="basic-addon1">
        </div>
      </div>
      <div class="col-md-3 col-sm-12">

        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
          </div>
          <input autocomplete="off" value="{{ $_GET['cliente'] ?? '' }}" type="text" id="cliente" name="cliente"
            class="form-control" placeholder="Cliente " aria-label="" aria-describedby="basic-addon1">
        </div>
      </div>
      <div class="col-md-3 col-sm-12">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">
                <i class="far fa-calendar-alt"></i>
              </span>
            </div>
            <input autocomplete="off" value="{{ $_GET['dataHora'] ?? '' }}" placeholder="Data de entrega" type="text"
              class="form-control float-right" name="dataHora" id="dataHora">
          </div>

        </div>
      </div>

      <div class="col-md-2 col-sm-12">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">
                <i class="fa fa-search"></i>
              </span>
            </div>
            <input autocomplete="off" value="{{ $_GET['codigo'] ?? '' }}" placeholder="ID" type="text"
              class="form-control float-right" name="codigo" id="codigo">
          </div>

        </div>
      </div>
      <div class="col-md-2 col-sm-12 mb-3">
        <a href="{{ route('pedido.index') }}" class="btn btn-primary w-100">Limpar busca</a>

      </div>
    </div>
    <div class="col-md-1">
    </div>
    <div class="row">
      <div class="col-md-1 col-sm-12">

        <select id="paginacao" name="paginacao" class="custom-select mr-2" style="min-width: 80px"
          id="inputGroupSelect01">

          <option value="10" {{ isset($_GET['paginacao']) && $_GET['paginacao'] == '10' ? 'selected' : '' }}>
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
      <div class="col-md-6 col-sm-12">

        {{ $producaos->appends([
            'paginacao' => $_GET['paginacao'] ?? 10,
            'motorista' => $_GET['motorista'] ?? '',
            'cliente' => $_GET['cliente'] ?? '',
            'dataHora' => $_GET['dataHora'] ?? '',
            'status' => $_GET['status'] ?? '',
        ]) }}
      </div>
    </div>


    <div class="d-flex flex-row input-group row justify-content-end">
      <div class="  ">

        <div class="col-10">

        </div>

      </div>
    </div>
  </form> --}}
    <div class="table-responsive">

        @if ($producaoCategoria)
            @foreach ($producaoCategoria as $categoria => $producaos)

            <div class="accordion" id="accordionExample">
              <div class="card card-primary">
                <div class="card-header" id="heading{{$loop->index}}">
                  <h2 class="mb-0">
                    <button class="btn  btn-block text-white text-left" style="font-size: 25px" type="button" data-toggle="collapse" data-target="#collapse{{$loop->index}}" aria-expanded="true" aria-controls="collapse{{$loop->index}}">
                    <b>{{ $categoria }}</b>
                    </button>
                  </h2>
                </div>
            
                <div id="collapse{{$loop->index}}" class="collapse " aria-labelledby="heading{{$loop->index}}" data-parent="#accordionExample">
                  <div class="card-body" style="background: #dedede">

                    <table id="pedidoTable" class="table shadow rounded table-striped table-hover">
  
                      <thead class="bg-primary ">
                          <tr>
                              <th>#</th>
                              <th>Produto</th>
                              <th>Quantidade</th>
                              <th>Status</th>
                              <th>Data de Início</th>
                              <th class="d-flex justify-content-center">Ações</th>
                          </tr>
                      </thead>
                      <tbody style="background:#fafafa">
  
                          @foreach ($producaos as $producao)
                              <tr @if ($producao->deleted_at != null) style="background-color:#ff8e8e" @endif>
  
                                  <td>{{ $producao->id }}</td>
                                  <td>{{ $producao->produto->nome }} - {{ $categoria }}</td>
                                  <td>{{ $producao->quantidade }}</td>
                                  <td>{{ $producao->status ? 'Concluído' : 'Pendente' }}</td>
                                  <td>{{ \Carbon\Carbon::parse($producao->dt_inicio)->format('d/m/Y H:i') }}</td>
                                  <td> <button data-id="{{$producao->id}}" type="button" class="btn btn-success confirmarProducao">OK<i class=" fa fa-2x fa-check"></i></button>
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
  
                  </table>
                  </div>
                </div>
              </div>
          
            </div>
               
            @endforeach
        @else
            <x-not-found />

        @endif

    </div>

@endsection

@push('scripts')
    <script>
        $('.confirmarProducao').on('click', async function() {
          console.log(this.dataset.id)
            fetch("confirmarProducao", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-Token": $('meta[name="csrf-token"]').val()
                },
                body: JSON.stringify({
                    producao_id: this.dataset.id,
                    _token: "{{ csrf_token() }}",

                })
            }).then((response) => {
                response.json().then((res) => {
                    console.log(res)
                    if (res.data.success && res.data == '') {
                        Toast.fire({
                            icon: 'success',
                            title: res.message
                        });
                    }

                    setTimeout(() => {
                        location.reload()
                    }, 1000);

                });

            }).catch((error) => {
                console.log(error)
            });
        })

    </script>
@endpush
