@extends('layouts.guest')

@section('content')
    <div>
        <p class="login-box-msg">Bem-vindo de volta</p>
        <p class="login-subtitle-text">Faça login para acessar o sistema</p>

        @if ($errors->any())
            <div class="alert alert-danger text-sm">
                <strong>Erro!</strong> Verifique os dados informados e tente novamente.
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="post" id="loginForm" autocomplete="on" novalidate>
            @csrf

            <div class="input-group mb-3 position-relative">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    placeholder="E-mail" required autofocus autocomplete="username" value="{{ old('email') }}">
                @error('email')
                    <span class="invalid-feedback d-block">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="input-group mb-3 position-relative">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="Senha" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback d-block">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <div class="icheck-primary">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">
                            Lembre-se de mim
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block" id="loginButton">
                <span class="login-button-text">Entrar</span>
                <span class="login-button-loading d-none">
                    <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>
                    Entrando...
                </span>
            </button>
        </form>

        @if (Route::has('password.request'))
            <p class="mb-0 mt-3 text-center">
                <a href="{{ route('password.request') }}">Esqueceu sua senha?</a>
            </p>
        @endif
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            const button = document.getElementById('loginButton');
            const text = button.querySelector('.login-button-text');
            const loading = button.querySelector('.login-button-loading');
            
            if (text && loading) {
                text.classList.add('d-none');
                loading.classList.remove('d-none');
                button.disabled = true;
            }
        });
    </script>
@endsection
