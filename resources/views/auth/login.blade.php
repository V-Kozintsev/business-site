@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <!-- Hero Header -->
            <div class="text-center mb-5">
                <div class="display-4 fw-bold text-primary mb-3">
                    <i class="bi bi-box-arrow-in-right me-3"></i>Вход
                </div>
                <p class="lead text-muted">Добро пожаловать обратно</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Form Card -->
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-envelope me-2 text-primary"></i>{{ __('Email') }}
                            </label>
                            <input id="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-lock me-2 text-primary"></i>{{ __('Пароль') }}
                            </label>
                            <input id="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                   type="password" name="password" required autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember_me" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="remember_me">
                                    <i class="bi bi-clock me-2"></i>{{ __('Запомнить меня') }}
                                </label>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold shadow-lg">
                                <i class="bi bi-door-open me-2"></i>{{ __('Войти') }}
                            </button>
                        </div>

                        <!-- Forgot Password -->
                        @if (Route::has('password.request'))
                            <div class="text-center mt-4 pt-3 border-top">
                                <a href="{{ route('password.request') }}" class="btn btn-link btn-lg p-0 text-decoration-none fw-semibold">
                                    <i class="bi bi-key me-2"></i>{{ __('Забыли пароль?') }}
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Register Link -->
            <div class="text-center mt-4">
                <p class="mb-0 text-muted">
                    Нет аккаунта? 
                    <a href="{{ route('register') }}" class="fw-bold text-primary text-decoration-none">
                        <i class="bi bi-person-plus me-1"></i>Зарегистрироваться
                    </a>
                </p>
            </div>

            <!-- Footer -->
            <div class="text-center mt-4">
                <small class="text-muted">
                    Безопасный вход • {{ config('app.name') }} © {{ date('Y') }}
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
