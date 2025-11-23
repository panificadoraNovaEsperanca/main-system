<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Padaria Nova Esperança') }} - Login</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('css/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    <!-- Login Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/login-custom.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-container">
    <div class="login-card">
        <div class="login-left">
            <h1 class="login-logo">Padaria Nova Esperança</h1>
            <p class="login-subtitle">Bem-vindo ao sistema de gestão</p>
        </div>
        <div class="login-right">
            @yield('content')
        </div>
    </div>
</div>

@vite('resources/js/app.js')
<!-- Bootstrap 4 -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('js/adminlte.min.js') }}" defer></script>
</body>
</html>
