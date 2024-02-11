@extends('layouts.app')
@section('title', 'Meu perfil')

@section('content')
    <!-- Content Header (Page header) -->

    <!-- /.content-header -->
    <div class="col-lg-6">

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">

                    <div class="input-group mb-3">
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="{{ __('Name') }}" value="{{ old('name', auth()->user()->name) }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        @error('name')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="{{ __('Email') }}" value="{{ old('email', auth()->user()->email) }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="{{ __('New password') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation"
                               class="form-control @error('password_confirmation') is-invalid @enderror"
                               placeholder="{{ __('New password confirmation') }}"
                               autocomplete="new-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                </div>

            </form>
    </div>

@endsection

