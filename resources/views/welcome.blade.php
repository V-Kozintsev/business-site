@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center mb-5">
            <h1 class="display-4 fw-bold mb-4">Business Site</h1>
            <p class="lead mb-4">Система отчетов и аналитики для бизнеса с Laravel 11 + Bootstrap 5</p>
            
            @auth
                <div class="d-grid gap-2 d-md-block">
                    <a href="/dashboard" class="btn btn-primary btn-lg me-md-2 mb-2">📊 Dashboard</a>
                    <a href="/admin-reports" class="btn btn-success btn-lg me-md-2 mb-2">🔥 Admin Reports</a>
                    <a href="/daily-reports" class="btn btn-info btn-lg mb-2">📈 Daily Reports</a>
                </div>
            @else
                <div class="d-grid gap-2 d-md-block">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg me-md-2 mb-2">🔐 Войти</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg mb-2">📝 Регистрация</a>
                </div>
            @endauth
        </div>

        <!-- Карточки фич -->
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-file-earmark-text fs-1 text-primary mb-3"></i>
                        <h5 class="card-title">Отчеты</h5>
                        <p class="card-text">CRUD операции для admin/daily reports с модальными окнами</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-shield-check fs-1 text-success mb-3"></i>
                        <h5 class="card-title">Роли</h5>
                        <p class="card-text">Авторизация + роли для доступа к отчетам</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-graph-up fs-1 text-info mb-3"></i>
                        <h5 class="card-title">Ajax</h5>
                        <p class="card-text">jQuery Ajax для динамических операций</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
