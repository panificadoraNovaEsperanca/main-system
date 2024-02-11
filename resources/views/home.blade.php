@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <form class="" id="formSearch" action="{{ route('home') }}" method="GET">

        <div class="d-flex">

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                </div>
                <input value="{{ $_GET['search'] ?? '' }}" style="" type="text" id="search"
                    name="search" class="form-control mr-2" placeholder="" aria-label="" aria-describedby="basic-addon1">
                <a href="{{ route('home') }}" class="btn btn-primary">Limpar busca</a>
            </div>
            {{ $categorias->links() }}
        </div>
        @if ($categorias->isNotEmpty())
            <div class="row ">
                @foreach ($categorias as $categoria)
                    <div class="col-4">
                        <div class="card">
                            <img src="{{ $categoria->url_capa }}" class="card-img-top" alt=""
                                style="height: 200px;width:765px">
                            <div class="px-3 py-1 d-flex align-items-start flex-column bd-highlight mb-3"
                                style="height: 200px;">
                                <div class="mb-auto p-2 bd-highlight">
                                    <h5 class="card-title">{{ $categoria->nome }}</h5>
                                    <p class="card-text">{{ $categoria->descricao }}</p>
                                </div>
                                <div class="p-2 bd-highlight"><a
                                        href="{{ route('produtosCategoria.index', $categoria->id) }}"
                                        class="btn btn-primary">Acessar produtos</a></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-not-found />
        @endif
    </form>

    <script>
        document.getElementById('search').addEventListener('change', function() {
            document.getElementById('formSearch').submit()
        })
    </script>

    <!-- /.content -->
@endsection
