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
                    <div class="col-md-4 d-flex gap-2 align-items-end">
    <button onclick="loadAdminReports()" class="btn btn-primary flex-fill">
        <i class="bi bi-arrow-clockwise me-2"></i>Обновить
    </button>
    <button onclick="clearFilters()" class="btn btn-outline-secondary flex-fill">
        <i class="bi bi-arrow-repeat me-2"></i>Сбросить
    </button>
</div>

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
    let url = '/api/reports?all=true';
    
    if (from || to) {
        url += `&date_from=${from}&date_to=${to}`;
    }
    
    console.log('Admin URL:', url);
    
    $.get(url)
        .done(function(data) {
            console.log('Admin count:', data.length);
            
            const tbody = $('#adminReportsTable');
            let totalRevenue = 0;
            tbody.empty();
            
            if (data.length === 0) {
                tbody.html('<tr><td colspan="4" class="text-center py-4 text-muted">Нет отчётов за период</td></tr>');
                $('#monthTotal').text('0 ₽');
                return;
            }
            
            data.forEach(report => {
                const revenueNum = parseFloat(report.revenue) || 0;  // ✅ Без NaN
                totalRevenue += revenueNum;
                
                const row = `
                    <tr>
                        <td>${report.employee_name || 'N/A'}</td>
                        <td>${new Date(report.report_date).toLocaleDateString('ru-RU')}</td>
                        <td>${report.sales_point || 'N/A'}</td>
                        <td class="text-end fw-bold">${report.revenue}₽</td>
                    </tr>`;
                tbody.append(row);
            });
            
            // ИТОГО в таблице
            tbody.append(`
                <tr class="table-success fw-bold">
                    <td colspan="3" class="text-end">ИТОГО:</td>
                    <td class="text-end">${totalRevenue.toLocaleString('ru-RU')} ₽</td>
                </tr>`);
            
            // Обнови карточку месяц
            $('#monthTotal').text(totalRevenue.toLocaleString('ru-RU') + ' ₽');
        })
        .fail(function(xhr) {
            console.error('API Error:', xhr);
            $('#adminReportsTable').html('<tr><td colspan="4" class="text-center py-4 text-danger">Ошибка API</td></tr>');
        });
}

function clearFilters() {
    $('#dateFrom, #dateTo').val('');
    loadAdminReports();
}
</script>

@endsection
