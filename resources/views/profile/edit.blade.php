@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Coming Soon Hero -->
            <div class="text-center p-5">
                <!-- Icon -->
                <div class="display-1 mb-4">
                    <i class="bi bi-gear-wide-connected text-primary"></i>
                </div>
                
                <!-- Title -->
                <h1 class="display-4 fw-bold text-dark mb-4">
                    Настройки профиля
                </h1>
                
                <!-- Subtitle -->
                <p class="lead text-muted mb-5 px-3">
                    Секция находится в разработке 🚀<br>
                    <small>Добавим редактирование, роли и многое другое</small>
                </p>

                <!-- Progress Bar -->
                <div class="progress mx-auto mb-4" style="width: 60%; height: 1.5rem;">
                    <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" 
                         role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
                        65%
                    </div>
                </div>

                <!-- Features Coming Soon -->
                <div class="row g-4 mt-5">
                    <div class="col-md-4">
                        <div class="card border-0 h-100 shadow-sm hover-shadow-lg">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                    <i class="bi bi-person-gear fs-4"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Редактирование</h5>
                                <p class="text-muted small mb-0">Имя, фото, контакты</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-0 h-100 shadow-sm hover-shadow-lg">
                            <div class="card-body text-center p-4">
                                <div class="bg-success text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                    <i class="bi bi-shield-check fs-4"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Управление ролями</h5>
                                <p class="text-muted small mb-0">Админ/Менеджер</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-0 h-100 shadow-sm hover-shadow-lg">
                            <div class="card-body text-center p-4">
                                <div class="bg-info text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                    <i class="bi bi-lock fs-4"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Безопасность</h5>
                                <p class="text-muted small mb-0">Пароль, 2FA</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="mt-5 pt-4 border-top">
                    <p class="text-muted mb-3">
                        Пока доступно:
                    </p>
                    <div class="row g-3 justify-content-center">
                        <div class="col-md-3">
                            <a href="/dashboard" class="btn btn-primary btn-lg w-100 py-3">
                                <i class="bi bi-house-door me-2"></i>На главную
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="/daily-reports" class="btn btn-outline-primary btn-lg w-100 py-3">
                                <i class="bi bi-file-earmark-text me-2"></i>Отчёты
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Info -->
                <div class="mt-5 p-4 bg-light rounded-3">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            <div class="bg-primary text-white rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 1.5rem;">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </div>
                        </div>
                        <div class="col-md-9">
                            <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                            <p class="text-muted mb-1">{{ Auth::user()->email }}</p>
                            <span class="badge bg-success fs-6">{{ Auth::user()->roles->pluck('name')->implode(', ') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-shadow-lg {
    transition: all 0.3s ease;
}
.hover-shadow-lg:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
}
</style>
@endsection
