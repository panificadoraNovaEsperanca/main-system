@extends('layouts.app')
@section('title', 'Baixa de Produção')

@section('actions')

@endsection


@section('content')


    <form class="mr-2 d-flex flex-column" id="formSearch" action="{{ route('producaoBaixa.index') }}" method="GET">
    <div class="row">
      <div class="col-md-3 col-sm-12">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">
                <i class="far fa-calendar-alt"></i>
              </span>
            </div>
            <input autocomplete="off" value="{{ $_GET['data'] ?? '' }}" placeholder="Data de entrega" type="text"
              class="form-control trigger float-right" name="data" id="data">
          </div>

        </div>
      </div>

      <div class="col-md-2 col-sm-12 mb-3">
        <a href="{{ route('producaoBaixa.index') }}" class="btn btn-primary w-100">Limpar busca</a>

      </div>
    </div>
    <div class="col-md-1">
    </div>


  </form>
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
                              <th>Observação</th>
                              <th>Usuário</th>
                              <th class="d-flex justify-content-center">Ações</th>
                          </tr>
                      </thead>
                      <tbody style="background:#fafafa">
  
                          @foreach ($producaos as $producao)
                              <tr @if ($producao->deleted_at != null) style="background-color:#ff8e8e" @endif>
  
                                  <td>{{ $producao->id }}</td>
                                  <td>{{ $producao->produto->nome }}</td>
                                  <td>{{ $producao->quantidade }}</td>
                                  <td>{{ $producao->status ? 'Concluído' : 'Pendente' }}</td>
                                  <td>{{ \Carbon\Carbon::parse($producao->dt_inicio)->format('d/m/Y H:i') }}</td>
                                  <td>{{ $producao->observacao ?? '-' }}</td>
                                  <td>{{ $producao->user ? ($producao->user->id . ' - ' . $producao->user->name) : 'N/A' }}</td>

                                  <td>
                                      <button data-id="{{$producao->id}}" data-status="{{$producao->status}}" type="button" class="btn {{$producao->status ? 'btn-success' : 'btn-danger'}} confirmarProducao">{{$producao->status ? 'Desfazer':'Ok'}}<i class=" fa  fa-check"></i>
                                  </button>
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
      
          $('#data').datetimepicker({
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
      format: 'd/m/Y',
      lang: 'pt'
    });
        $('.confirmarProducao').on('click', async function() {
          const producaoId = this.dataset.id;
          // Usar sempre o usuário logado (não precisa mais pedir)
          confirmarBaixa(producaoId);
        })
        
        function confirmarBaixa(producaoId) {
            fetch("confirmarProducao", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-Token": $('meta[name="csrf-token"]').val()
                },
                body: JSON.stringify({
                    producao_id: producaoId,
                    _token: "{{ csrf_token() }}",

                })
            }).then((response) => {
                response.json().then((res) => {
                    console.log(res)
                    if (res.success) {
                        Toast.fire({
                            icon: 'success',
                            title: res.message
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: res.message || 'Erro ao processar requisição'
                        });
                    }

                    setTimeout(() => {
                        location.reload()
                    }, 1000);

                });

            }).catch((error) => {
                console.log(error)
                Toast.fire({
                    icon: 'error',
                    title: 'Erro ao processar requisição'
                });
            });
        }
        $('.trigger').on('change',function(){
          document.getElementById('formSearch').submit()

        })
        $('.dataHora').datetimepicker({
            i18n: {
                de: {

                }
            },
            format: 'd/m/Y H:i',
            lang: 'pt'
        });
    </script>
@endpush
