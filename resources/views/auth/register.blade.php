@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <!-- Hero Header -->
            <div class="text-center mb-5">
                <div class="display-5 fw-bold text-primary mb-3">
                    <i class="bi bi-person-plus me-3"></i>Регистрация
                </div>
                <p class="lead text-muted">Присоединяйся к системе отчётов</p>
            </div>

            <!-- Form Card -->
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-person me-2 text-primary"></i>{{ __('Имя') }}
                            </label>
                            <input id="name" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-envelope me-2 text-primary"></i>{{ __('Email') }}
                            </label>
                            <input id="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
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
                                   type="password" name="password" required autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-5">
                            <label class="form-label fw-bold">
                                <i class="bi bi-lock-fill me-2 text-primary"></i>{{ __('Подтвердите пароль') }}
                            </label>
                            <input id="password_confirmation" class="form-control form-control-lg" 
                                   type="password" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <!-- Buttons -->
                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold shadow-lg">
                                <i class="bi bi-check-circle me-2"></i>{{ __('Зарегистрироваться') }}
                            </button>
                            
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg py-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i>{{ __('Уже есть аккаунт? Войти') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-4">
                <small class="text-muted">
                    Защищено • {{ config('app.name') }} © {{ date('Y') }}
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
