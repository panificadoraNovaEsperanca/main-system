@extends('layouts.app')
@section('title', 'Produtos')

@section('actions')
    <a href="{{ route('produto.create') }}" class="btn btn-primary">
        Cadastrar produto
    </a>
@endsection


@section('content')
    <form class="d-flex flex-row justify-content-around" id="formSearch" action="{{ route('produto.index') }}" method="GET">


        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
            </div>
            <input value="{{ $_GET['search'] ?? '' }}" style="max-width: 150px" type="text" id="search" name="search"
                class="form-control mr-2" placeholder="" aria-label="" aria-describedby="basic-addon1">
            <a href="{{ route('produto.index') }}" class="btn btn-primary">Limpar busca</a>

        </div>
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
            </div>
            {{ $produtos->appends(['paginacao' => $_GET['paginacao'] ?? 10]) }}
        </div>
    </form>
    @if (!$produtos->isEmpty())
        <table id="produtosTable" class="table shadow rounded table-striped table-hover">
            <thead class="bg-primary ">
                <tr>
                    <th>Id</th>
                    <th>Nome</th>
                    <th>Categoria</th>

                    <th>Descrição</th>
                    <th>Responsável</th>
                    <th>Ativo</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="">
                @foreach ($produtos as $produto)
                    <tr @if ($produto->deleted_at != null) style="background-color:#ff8e8e" @endif >
                        
                        <td>{{ $produto->id }}
                        </td>

                        <td>{{ $produto->nome }}</td>
                        <td>{{ $produto->categoria->nome }}</td>

                        <td style="text-overflow: ellipsis">{{ $produto->descricao }}</td>
                        <td>{{ $produto->responsavel->name }}</td>
                        <td>{{ $produto->deleted_at == null ? 'Ativo' : 'Inativo' }}</td>


                        <td class="d-flex  justify-content-around">
                            <button data-id="{{ $produto->id }}" type="button" class="btn btn-primary infoProduto mr-1">
                                <i class="fas fa-info"></i>
                            </button>
                            @if ($produto->deleted_at == null)
                            <a href="{{ route('produto.edit', $produto->id) }}" type="button"
                                class="btn btn-warning mr-1 editModal"><i class="fas fa-edit"></i></a>
                        @endif
                        <form method="POST"
                            action="{{ route($produto->deleted_at == null ? 'produto.destroy' : 'produto.ativar', $produto->id) }}"
                            enctype="multipart/form-data">
                            @if ($produto->deleted_at == null)
                                @method('DELETE')
                            @else
                                @method('PUT')
                            @endif
                            @csrf
                            <button type="submit"
                                class="btn {{ $produto->deleted_at == null ? 'btn-danger' : 'btn-success' }}"><i
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
    @include('produto.modalInfo')

@endsection

@push('scripts')
    <script>
        $('.infoProduto').on('click', function() {
            console.log(this)
            fetch(`/produto/${this.dataset.id}`).then(async (response) => {
                let result = await response.json();
                console.log(result)
                if (result.success && Object.keys(result.data).length > 0) {
                    document.getElementById('nomeProduto').textContent = result.data.nome

                    document.getElementById('codigoProduto').value = result.data.id

                    document.getElementById('unidadeMedida').value = result.data.unidade_medida

                    document.getElementById('porcao').value = result.data.informacao_nutricional.porcao
                    document.getElementById('proteina').value = result.data.informacao_nutricional
                        .proteina
                    document.getElementById('carboidrato').value = result.data.informacao_nutricional
                        .carboidrato
                    document.getElementById('gordura_total').value = result.data.informacao_nutricional
                        .gordura_total


                    document.getElementById('fornecedorNome').value = result.data.fornecedor.nome
                    document.getElementById('nomeProduto').value = result.data.nome

                    let date = new Date(Date.parse(result.data.created_at));
                    let dia = date.getDate().toString().padStart(2, '0');
                    let mes = (date.getMonth() + 1).toString().padStart(2, '0');
                    let ano = date.getFullYear();
                    document.getElementById('dataCadastro').value = `${dia}/${mes}/${ano}`

                    document.getElementById('responsavel').value = result.data.responsavel.name
                    document.getElementById('descricaoProduto').value = result.data.descricao
                    $('#produtoModal').modal('show')

                } else {
                    Toast.fire({
                        heightAuto: true,
                        icon: 'error',
                        title: 'Produto não encontrado'
                    });
                }

            })

        })

        document.getElementById('produtoModal').addEventListener('hide.bs.modal', function() {
            document.getElementById('formProduto').reset()
        })
        document.getElementById('search').addEventListener('change', function() {
            document.getElementById('formSearch').submit()
        })
        document.getElementById('paginacao').addEventListener('change', function() {
            document.getElementById('formSearch').submit()
        })
    </script>
@endpush
