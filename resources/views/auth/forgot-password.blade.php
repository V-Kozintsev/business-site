@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <!-- Hero Header -->
            <div class="text-center mb-5">
                <div class="display-5 fw-bold text-warning mb-3">
                    <i class="bi bi-key me-3"></i>Восстановление пароля
                </div>
                <p class="lead text-muted">Введите email — отправим ссылку для сброса</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Form Card -->
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Info Text -->
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            Введите адрес электронной почты, и мы отправим вам ссылку для сброса пароля.
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-envelope me-2 text-warning"></i>{{ __('Email') }}
                            </label>
                            <input id="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning btn-lg py-3 fw-bold shadow-lg">
                                <i class="bi bi-envelope-arrow-up me-2"></i>{{ __('Отправить ссылку') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Back to Login -->
            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg py-2 px-4">
                    <i class="bi bi-arrow-left me-2"></i>Вернуться ко входу
                </a>
            </div>

            <!-- Footer -->
            <div class="text-center mt-4">
                <small class="text-muted">
                    Безопасное восстановление • {{ config('app.name') }} © {{ date('Y') }}
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
