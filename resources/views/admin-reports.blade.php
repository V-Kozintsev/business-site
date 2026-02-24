@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-8">
    <!-- Заголовок -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="display-5 fw-bold">📊 Админ: Все отчёты</h1>
        <span class="badge bg-primary fs-6 px-3 py-2">{{ auth()->user()->name }}</span>
    </div>

    <!-- ФИЛЬТРЫ -->
    <div class="card mb-5 shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">От:</label>
                    <input type="date" id="dateFrom" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">До:</label>
                    <input type="date" id="dateTo" class="form-control">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button onclick="loadAdminReports()" class="btn btn-primary w-100">
                        <i class="bi bi-arrow-clockwise me-2"></i>Обновить
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ТАБЛИЦА -->
    <div class="card shadow-lg">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">📋 Список отчётов</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Менеджер</th>
                            <th>Дата</th>
                            <th>Точка</th>
                            <th class="text-end">Выручка</th>
                        </tr>
                    </thead>
                    <tbody id="adminReportsTable">
                        <tr><td colspan="4" class="text-center py-4 text-muted">Загрузка...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ИТОГО -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card bg-success text-white shadow">
                <div class="card-body text-center">
                    <h5>📈 Выручка месяц</h5>
                    <h2 id="monthTotal" class="fw-bold">0 ₽</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

$(document).ready(function() {
    loadAdminReports();
});

function loadAdminReports() {
    const from = $('#dateFrom').val();
    const to = $('#dateTo').val();
    let url = '/api/reports';
    
    if (from || to) {
        url += '?';
        if (from) url += 'date_from=' + from + '&';
        if (to) url += 'date_to=' + to;
        url = url.slice(0, -1);
    }

    $.get(url, function(data) {
        const tbody = $('#adminReportsTable');
        let totalRevenue = 0;
        
        tbody.empty();
        data.forEach(report => {
            totalRevenue += parseFloat(report.revenue) || 0;
            tbody.append(`
                <tr>
                    <td>${report.employee_name || 'Неизвестно'}</td>
                    <td>${report.report_date}</td>
                    <td>${report.sales_point}</td>
                    <td class="text-end fw-bold text-success fs-5">${report.revenue.toLocaleString()} ₽</td>
                </tr>
            `);
        });
        
        // Итог
        tbody.append(`
            <tr class="table-info fw-bold">
                <td colspan="3" class="text-end">Итого:</td>
                <td class="text-end fs-4">${totalRevenue.toLocaleString()} ₽</td>
            </tr>
        `);
        
        // Месяц
        const now = new Date();
        const monthReports = data.filter(r => {
            const date = new Date(r.report_date);
            return date.getMonth() === now.getMonth() && date.getFullYear() === now.getFullYear();
        });
        const monthTotal = monthReports.reduce((sum, r) => sum + parseFloat(r.revenue || 0), 0);
        $('#monthTotal').text(monthTotal.toLocaleString() + ' ₽');
        
    }).fail(function() {
        $('#adminReportsTable').html('<tr><td colspan="4" class="text-center py-4 text-danger">Ошибка загрузки</td></tr>');
    });
}
</script>
@endsection
