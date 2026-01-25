@extends('layouts.app')
@section('title', 'Relatório de Rastreabilidade')

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-file-pdf"></i> Relatório de Rastreabilidade
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('rastreabilidade.relatorio.gerar') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Data do Relatório <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="date" 
                                    class="form-control" 
                                    name="data" 
                                    value="{{ old('data', date('Y-m-d')) }}"
                                    required>
                            </div>
                            @error('data')
                                <span class="text-danger"><small>{{ $message }}</small></span>
                            @enderror
                            <small class="text-muted">Selecione a data para gerar o relatório de rastreabilidade</small>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-file-pdf"></i> Gerar Relatório PDF
                </button>
                <a href="{{ route('rastreabilidade.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </form>
        </div>
    </div>

@endsection
