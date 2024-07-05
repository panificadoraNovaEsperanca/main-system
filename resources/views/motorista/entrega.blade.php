@extends('layouts.app')
@section('title', 'Motorista')

@section('actions')
  
@endsection


@section('content')

  <form class="mr-2 d-flex justify-content-between" id="formSearch" action="{{ route('motorista.index') }}" method="GET">

    <div class="d-flex">
      <div class="input-group  ">
        <select id="paginacao" name="paginacao" class="custom-select mr-2" style="min-width: 80px"
          id="inputGroupSelect01">
          <option value="10" {{ isset($_GET['paginacao']) && $_GET['paginacao'] == '10' ? 'selected' : '' }}>
            10
          </option>
          <option value="20" {{ isset($_GET['paginacao']) && $_GET['paginacao'] == '20' ? 'selected' : '' }}>
            20
          </option>
          <option value="30" {{ isset($_GET['paginacao']) && $_GET['paginacao'] == '30' ? 'selected' : '' }}>
            30
          </option>


        </select>
        {{ $pedidos->appends([
            'paginacao' => $_GET['paginacao'] ?? 10,
            'search' => $_GET['search'] ?? '',
        ]) }}

      </div>
    </div>
  </form>
  @if (!$pedidos->isEmpty())

  <form method="POST" action="{{ route('pedido.atualizar') }}">
    @csrf
    <div class="table-responsive">

        <table id="pedidoTable" class="table shadow rounded table-striped table-hover">


            <thead class="bg-primary ">
              <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>Id</th>
                <th>Cliente</th>
                <th>Status</th>
                <th>Data de entrega</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($pedidos as $pedido)
                <tr @if ($pedido->deleted_at != null) style="background-color:#ff8e8e" @endif>
                  <td><input style="height: 30px;width:30px" type="checkbox" class="pedidoCheck" name="pedido[]" value="{{ $pedido->id }}">
                  </td>
                  <td>{{ $pedido->id }}</td>
                  <td>{{ $pedido->cliente->name }}</td>
                  <td>{{ $pedido->status }}</td>
                  <td>{{ \Carbon\Carbon::parse($pedido->dt_previsao)->format('d/m/Y H:i') }}</td>
                </tr>
              @endforeach
            </tbody>
      
          </table>
    </div>

    <div class="d-flex  align-items-center">
      <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
          <label>Status</label>
          <div class="input-group">
            <select class="custom-select" name="status">
              <option hidden>Selecione uma opção</option>
              <option {{ isset($pedido) && $pedido->status == 'AGENDADO' ? 'selected' : '' }} value="AGENDADO">
                Agendado</option>
              <option {{ isset($pedido) && $pedido->status == 'ENTREGUE' ? 'selected' : '' }} value="ENTREGUE">
                Entregue</option>
              <option {{ isset($pedido) && $pedido->status == 'CANCELADO' ? 'selected' : '' }} value="CANCELADO">
                Cancelado</option>

            </select>
          </div>
          @error('status')
            <span class="mt-1  text-red p-1 rounded"><small>{{ $message }}</small></span>
          @enderror
        </div>
      </div>
      <div class="col-lg-1 col-md-6 col-sm-12">
        <button class="btn btn-primary" type="submit">Atualizar Pedido</button>
      </div>
    </div>
  </form>
@else
  <x-not-found />

@endif

  <!-- Modal -->



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