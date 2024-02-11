<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Controle de estoque') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
    integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @notifyCss
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">


    @yield('styles')
</head>

<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <nav class="main-header navbar navbar-expand navbar-light navbar-white d-flex justify-content-between">
            <ul class="navbar-nav">

            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
            </li>
            </ul>
            <div class="">

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                  
                    <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa-solid fa-user mr-2"></i>
                        {{ Auth::user()->name }}
                    </a>
                        <div class="dropdown-menu dropdown-menu-right" style="left: inherit; right: 0px;">
                            <a href="{{ route('profile.show') }}" class="dropdown-item">
                                <i class="mr-2 fas fa-file"></i>
                                {{ __('Meu perfil') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" class="dropdown-item"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="mr-2 fas fa-sign-out-alt"></i>
                                    {{ __('Log Out') }}
                                </a>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- /.navbar -->
   
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color:#1e0535">
            <!-- Brand Logo -->
            <a href="/home" class="brand-link  d-flex align-items-center flex-column">
                <img src="https://norven.com.br/wp-content/themes/norven/images/logo-footer.png" alt="AdminLTE Logo"
                    class="" style="opacity: .8">
                <span class="brand-text d-none text-center font-weight-light">Controle de Estoque</span>
            </a>

            @include('layouts.navigation')
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2 d-flex justify-content-between">
                            <div class="d-flex justify-content around align-items-center">
                                <h1 class="mr-3">@yield('title', 'Página Principal')</h1> @yield('tooltip')
                            </div><!-- /.col -->
                            <div class=" ">
                                @yield('actions')
                            </div>
                        </div>

                        <!-- /.row -->
                    </div>

                    <!-- /.container-fluid -->
                </div>
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        @yield('content')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
            </div>
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->
        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                Anything you want
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights
            reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    @if (\Session::has('messages'))
        <input type="hidden" name="messages" id="messages" value="{{ json_encode(\Session::get('messages')) }}">
    @endif

    @vite('resources/js/app.js')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="{{ asset('js/adminlte.min.js') }}" defer></script>

    @yield('scripts')
    @stack('scripts')

    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast',
            },
            animation: true,
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
        })

        if (document.getElementById('messages') != null) {
            const messages = JSON.parse(document.getElementById('messages').value);
            for (let item in messages) {
                for (let message of messages[item]) {
                    Toast.fire({
                        heightAuto: false,

                        icon: `${item}`,
                        title: `${message}`
                    });
                }
            }
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

</body>

</html>
