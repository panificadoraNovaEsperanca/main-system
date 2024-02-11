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
        </div>
     
    </form>

    <script>
     
    </script>

    <!-- /.content -->
@endsection
