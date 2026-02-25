@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Заголовок -->
<section class="py-5 bg-gradient" style="background: linear-gradient(135deg, #0d6efd, #6610f2); color: white;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold mb-2">📊 Ежедневные отчёты</h1>
                <p class="lead mb-0">
                    Февраль 2026 • <span id="reportsCount">0</span> отчётов
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <button class="btn btn-light btn-lg px-4 shadow" data-bs-toggle="modal" data-bs-target="#reportModal">
                    <i class="bi bi-plus-circle me-2"></i>Новый отчёт
                </button>
            </div>
        </div>
    </div>
</section>

<!-- МОДАЛКА -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">➕ Новый отчёт</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="reportForm">
    @csrf
    <input type="hidden" name="employee_id" value="{{ Auth::id() }}">

    <!-- ✅ ФИО ВИДИМОЕ ПОЛЕ -->
    <div class="mb-3">
        <label class="form-label fw-bold">👤 ФИО менеджера:</label>
        <input type="text" class="form-control" name="employee_name" 
               value="{{ Auth::user()->name }}" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label fw-bold">📍 Адрес точки продаж:</label>
        <input type="text" class="form-control" name="sales_point" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label fw-bold">💰 Выручка за день:</label>
        <input type="number" step="0.01" class="form-control" name="revenue" required min="0">
    </div>
    
    <div class="mb-3">
        <label class="form-label fw-bold">📅 Дата:</label>
        <input type="date" class="form-control" name="report_date" required>
    </div>
</form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" onclick="saveReport()">
                    <i class="bi bi-check-circle me-2"></i>Сохранить
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ТАБЛИЦА -->
<div class="container py-5">
    <div class="card shadow-lg border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark sticky-top">
                        <tr>
                            <th class="border-0 py-4 px-4 fw-bold text-white">#</th>
                            <th class="border-0 py-4 px-4 fw-bold text-white">👤 ФИО</th>
                            <th class="border-0 py-4 px-4 fw-bold text-white">📅 Дата</th>
                            <th class="border-0 py-4 px-4 fw-bold text-white">📍 Точка</th>
                            <th class="border-0 py-4 px-4 fw-bold text-white text-end">💰 Выручка</th>
                            <th class="border-0 py-4 px-4 fw-bold text-white text-center">⚙️ Действия</th>
                        </tr>
                    </thead>
                    <tbody id="reportsTableBody">
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="spinner-border text-primary me-2" role="status"></div>
                                Загрузка отчётов...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Пустое состояние -->
    <div id="emptyState" class="text-center py-8 mt-4">
        <div class="card border-dashed border-3 border-secondary-subtle shadow-sm">
            <div class="card-body text-center p-5">
                <i class="bi bi-file-earmark-text fs-1 text-muted mb-4"></i>
                <h4 class="text-muted mb-3">Нет отчётов за текущий месяц</h4>
                <p class="text-muted mb-4">Создайте первый отчёт для <strong>февраля 2026</strong></p>
                <button class="btn btn-outline-primary btn-lg px-4" data-bs-toggle="modal" data-bs-target="#reportModal">
                    <i class="bi bi-plus-circle me-2"></i>➕ Создать отчёт
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
 $('#reportModal').on('hidden.bs.modal', function () {
    // Bootstrap уже очистил, но на всякий — финальная проверка
    $('body').removeClass('modal-open');
    $('body').removeAttr('style');
    document.body.style.overflow = 'auto';
    $('.modal-backdrop').remove();
    console.log('Modal полностью закрыт — скролл восстановлен');
});
    // ✅ CSRF для всех Ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    });
    
    console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
    loadReports();
    
    // Edit handler
    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const sales = $(this).data('sales');
        const revenue = $(this).data('revenue');
        const date = $(this).data('date');
        
        $('#reportForm input[name="employee_name"]').val(name);
        $('#reportForm input[name="sales_point"]').val(sales);
        $('#reportForm input[name="revenue"]').val(revenue);
        $('#reportForm input[name="report_date"]').val(date);
        $('#reportForm').data('report-id', id);
        $('#modalTitle').text('✏️ Изменить отчёт');
        $('.btn-primary[onclick="saveReport()"]').html('<i class="bi bi-check-circle me-2"></i>Обновить');
        
        $('#reportModal').modal('show');
    });
    
    // Delete handler
    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        if(confirm('Удалить этот отчёт?')) {
            $.ajax({
                method: 'DELETE',
                url: `/api/reports/${id}`,
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: loadReports,
                error: (xhr) => {
                    console.error('Delete error:', xhr);
                    alert('Ошибка удаления');
                }
            });
        }
    });

});

function loadReports() {
    $.get('/api/reports')
    .done(function(data) {
        const tbody = $('#reportsTableBody');
        const emptyState = $('#emptyState');
        
        tbody.empty();
        
        if(data.length === 0) {
            emptyState.show();
            $('#reportsCount').text('0');
            return;
        }
        
        emptyState.hide();
        $('#reportsCount').text(data.length);
        
        const currentMonth = new Date().getMonth();
        const currentYear = new Date().getFullYear();
        
        data.forEach((report, index) => {
            const reportDate = new Date(report.report_date);
            const isCurrentMonth = reportDate.getMonth() === currentMonth && 
                                 reportDate.getFullYear() === currentYear;
            
            tbody.append(`
                <tr>
                    <td class="fw-bold py-3 px-4">${index + 1}</td>
                    <td class="py-3 px-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-person fs-6"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">${report.employee_name || 'Неизвестно'}</div>
                                <small class="text-muted">ID: ${report.id}</small>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <span class="badge ${isCurrentMonth ? 'bg-success' : 'bg-secondary'}">
                            ${new Date(report.report_date).toLocaleDateString('ru-RU')}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                        ${report.sales_point}
                    </td>
                    <td class="py-3 px-4 text-end fw-bold text-success fs-5">
                        ${parseFloat(report.revenue).toLocaleString('ru-RU')} ₽
                    </td>
                    <td class="py-3 px-4 text-center">
                        ${isCurrentMonth ? `
                            <div class="btn-group-vertical btn-group-sm d-flex justify-content-center gap-1" role="group">
                                <button class="btn btn-outline-warning btn-sm edit-btn shadow-sm w-100" 
                                  data-id="${report.id}" 
                                  data-name="${report.employee_name}" 
                                  data-sales="${report.sales_point}" 
                                  data-revenue="${report.revenue}" 
                                  data-date="${report.report_date}">
                                  <i class="bi bi-pencil"></i> Изменить
                                </button>
                                <button class="btn btn-outline-danger btn-sm delete-btn shadow-sm w-100" 
                                        data-id="${report.id}">
                                    <i class="bi bi-trash"></i> Удалить
                                </button>
                            </div>
                        ` : `
                            <div class="text-muted text-center">
                                <i class="bi bi-eye fs-4" title="Только просмотр"></i>
                                <div class="small mt-1">Архив</div>
                            </div>
                        `}
                    </td>
                </tr>
            `);
        });
    })
    .fail((xhr) => {
        console.error('API error:', xhr);
        $('#reportsTableBody').html(`
            <tr><td colspan="6" class="text-center py-5 text-danger">
                <i class="bi bi-exclamation-triangle fs-1 mb-3 d-block"></i>
                Ошибка загрузки отчётов
            </td></tr>
        `);
    });
}

function saveReport() {
    const reportId = $('#reportForm').data('report-id');
    const $form = $('#reportForm');
    
    if(!$form[0].checkValidity()) {
        $form[0].reportValidity();
        return;
    }
    
    const formReset = function() {
        $form[0].reset();
        $form.removeData('report-id');
        $('#modalTitle').text('➕ Новый отчёт');
        $('.btn-primary[onclick="saveReport()"]').html('<i class="bi bi-check-circle me-2"></i>Сохранить');
    };
    
    if(reportId) {
        // UPDATE
        $.ajax({
            method: 'PUT',
            url: `/api/reports/${reportId}`,
            data: $form.serialize(),
            success: function() {
                formReset();
                $('#reportModal').modal('hide');  // Только hide!
                loadReports();
            }
        });
    } else {
        // CREATE
        $.post('/api/reports', $form.serialize())
        .done(function() {
            formReset();
            $('#reportModal').modal('hide');  // Только hide — без cleanupModal!
            loadReports();
        });
    }
}

</script>
@endsection
