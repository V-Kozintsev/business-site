@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">
    <!-- Hero -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <div class="display-4 fw-bold text-primary mb-3">👋 Привет, {{ Auth::user()->name }}!</div>
            <p class="lead text-muted">Панель управления • Февраль 2026</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-lg border-0 h-100 text-center p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="card-body">
                    <i class="bi bi-file-earmark-text fs-1 mb-3 opacity-75"></i>
                    <h3 class="display-6 fw-bold mb-1">{{ rand(120, 150) }}</h3>
                    <p class="mb-0">Всего отчётов</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow-lg border-0 h-100 text-center p-4" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <div class="card-body">
                    <i class="bi bi-cash-stack fs-1 mb-3 opacity-75"></i>
                    <h3 class="display-6 fw-bold mb-1">{{ number_format(rand(2500000, 4500000), 0, '.', ' ') }} ₽</h3>
                    <p class="mb-0">Выручка месяц</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow-lg border-0 h-100 text-center p-4" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                <div class="card-body">
                    <i class="bi bi-calendar-check fs-1 mb-3 opacity-75"></i>
                    <h3 class="display-6 fw-bold mb-1">{{ rand(8, 15) }}</h3>
                    <p class="mb-0">Сегодня</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow-lg border-0 h-100 text-center p-4" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                <div class="card-body">
                    <i class="bi bi-people fs-1 mb-3 opacity-75"></i>
                    <h3 class="display-6 fw-bold mb-1">{{ rand(5, 12) }}</h3>
                    <p class="mb-0">Менеджеров</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Quick Actions -->
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-4">
                    <h5 class="mb-0"><i class="bi bi-lightning-charge me-2"></i>Быстрые действия</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="/daily-reports" class="btn btn-primary btn-lg w-100 py-3">
                                <i class="bi bi-plus-circle me-2"></i>Новый отчёт
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="/admin-reports" class="btn btn-success btn-lg w-100 py-3">
                                <i class="bi bi-people me-2"></i>Все отчёты
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="/profile" class="btn btn-secondary btn-lg w-100 py-3">
                                <i class="bi bi-gear me-2"></i>Профиль
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-header bg-warning text-dark py-4">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Последние</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-4 py-3 border-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    ИВ
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Иванов Иван</div>
                                    <small class="text-muted">ТЦ "Красная Площадь"</small>
                                </div>
                                <div class="text-success fw-bold fs-6">1 250 000 ₽</div>
                            </div>
                        </div>
                        <div class="list-group-item px-4 py-3 border-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    ПС
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Петров Сергей</div>
                                    <small class="text-muted">ТЦ "Галерея"</small>
                                </div>
                                <div class="text-success fw-bold fs-6">980 500 ₽</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Info -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-person-circle me-2"></i>
                {{ Auth::user()->name }} • 
                <span class="badge bg-primary">{{ Auth::user()->roles->pluck('name')->implode(', ') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
