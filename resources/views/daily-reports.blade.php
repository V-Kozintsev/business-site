<x-app-layout>
    <div class="card border-0 shadow-lg mb-4 overflow-hidden">
  <div class="card-body p-4" style="background: linear-gradient(135deg, #0d6efd, #6610f2);">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h2 class="text-white mb-1 fw-bold">
          📊 Ежедневные отчёты
        </h2>
        <p class="text-white-50 mb-0">
          Февраль 2026 • <span id="reportsCount">0</span> отчётов
        </p>
      </div>
      <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <button class="btn btn-light btn-lg px-4 shadow" data-bs-toggle="modal" data-bs-target="#reportModal">
          ➕ Новый отчёт
        </button>
      </div>
    </div>
  </div>
</div>


    <!-- МОДАЛКА REPORT (вставь СЮДА после </div> p-6) -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Новый отчёт</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="reportForm">
                    <input type="hidden" name="employee_id" value="{{ Auth::id() }}">
                    <div class="mb-3">
                        <label class="form-label">Адрес точки продаж:</label>
                        <input type="text" class="form-control" name="sales_point" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Выручка за день:</label>
                        <input type="number" step="0.01" class="form-control" name="revenue" required max="99999999">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Дата:</label>
                        <input type="date" class="form-control" name="report_date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" onclick="saveReport()">Сохранить</button>
            </div>
        </div>
    </div>
</div>


    <!-- Карточка таблицы с Grid -->
    <div class="card shadow-lg border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark sticky-top">
                        <tr>
                            <th class="border-0 py-3 px-4 fw-bold text-white">👤 ФИО</th>
                            <th class="border-0 py-3 px-4 fw-bold text-white">📅 Дата</th>
                            <th class="border-0 py-3 px-4 fw-bold text-white">📍 Точка</th>
                            <th class="border-0 py-3 px-4 fw-bold text-white text-end">💰 Выручка</th>
                            <th class="border-0 py-3 px-4 fw-bold text-white text-center">⚙️ Действия</th>
                        </tr>
                    </thead>
                    <tbody id="reportsTableBody" class="table-group-divider">
                        <!-- JS заполнит -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Пустое состояние -->
    <div id="emptyState" class="text-center py-8 mt-4">
        <div class="card border-dashed border-3 border-secondary-subtle shadow-sm">
            <div class="card-body text-center p-5">
                <i class="bi bi-file-earmark-text fs-1 text-muted mb-3"></i>
                <h5 class="text-muted mb-2">Нет отчётов за текущий месяц</h5>
                <p class="text-muted mb-4">Создайте первый отчёт для <strong>февраля 2026</strong></p>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#reportModal">
                    ➕ Создать отчёт
                </button>
            </div>
        </div>
    </div>
</div>

            </div>
        </div>
    </div>
    <style>
.btn-outline-warning:hover { 
  background: #ffc107 !important; color: #000 !important; transform: translateY(-1px); 
}
.btn-outline-danger:hover { 
  background: #dc3545 !important; color: white !important; transform: translateY(-1px); 
}
.table-dark th { 
  background: linear-gradient(90deg, #1e3a8a, #3b82f6) !important; 
}
</style>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    $(document).ready(function() {
    // CSRF token для Ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    loadReports();

     $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        const sales = $(this).data('sales');
        const revenue = $(this).data('revenue');
        const date = $(this).data('date');
        
        // Заполняем форму данными
        $('#reportForm input[name="sales_point"]').val(sales);
        $('#reportForm input[name="revenue"]').val(revenue);
        $('#reportForm input[name="report_date"]').val(date);
        
        // Меняем кнопку на "Обновить"
        $('.modal-title').text('Изменить отчёт');
        $('.btn-primary[onclick="saveReport()"]').text('Обновить');
        
        // Сохраняем ID для update
        $('#reportForm').data('report-id', id);
        
        // Показываем модал
        $('#reportModal').modal('show');
    });
    $(document).on('click', '.delete-btn', function() {
    const id = $(this).data('id');
    if (confirm('Удалить отчёт?')) {
        $.ajax({
            url: `/api/reports/${id}`,
            method: 'DELETE',
            success: function() {
                loadReports();
            },
            error: function() {
                alert('Ошибка удаления');
            }
        });
    }
});

   

})

    function loadReports() {
    $.get('/api/reports', function(data) {
        const tbody = $('#reportsTableBody');
        const emptyState = $('#emptyState');
        
        tbody.empty();
        
        if (data.length === 0) {
            emptyState.show();
            return;
        }
        emptyState.hide();
        
        data.forEach(report => {
            $('#reportsCount').text(data.length);
            tbody.append(`
                <tr class="animate__animated animate__fadeIn">
                    <td class="py-3 px-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-person fs-6"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">${report.employee_name || 'Неизвестно'}</div>
                                <small class="text-muted">ID: ${report.id}</small>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <span class="badge bg-light text-dark">${new Date(report.report_date).toLocaleDateString('ru-RU')}</span>
                    </td>
                    <td class="py-3 px-4">
                        <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                        ${report.sales_point}
                    </td>
                    <td class="py-3 px-4 text-end fw-bold text-success fs-5">
                        ${parseFloat(report.revenue).toLocaleString('ru-RU')} ₽
                    </td>
                    <td class="py-3 px-4 text-center">
                        <div class="btn-group-vertical btn-group-sm d-flex justify-content-center" role="group">
  <button class="btn btn-outline-warning btn-sm edit-btn shadow-sm mb-1 w-100" 
          data-id="${report.id}" data-sales="${report.sales_point}" 
          data-revenue="${report.revenue}" data-date="${report.report_date}"
          title="Изменить">
    <i class="bi bi-pencil"></i> Изменить
  </button>
  <button class="btn btn-outline-danger btn-sm delete-btn shadow-sm w-100" 
          data-id="${report.id}" title="Удалить">
    <i class="bi bi-trash"></i> Удалить
  </button>
</div>

                    </td>
                </tr>
            `);
        });
    }).fail(function() {
        console.log('API error');
    });
}



    function saveReport() {
    const reportId = $('#reportForm').data('report-id');
    const $form = $('#reportForm');
    
    if ($form[0].checkValidity()) {
        if (reportId) {
            // UPDATE — твой код OK
            $.ajax({
                url: `/api/reports/${reportId}`,
                method: 'PUT',
                data: $form.serialize(),
                success: function() {
                    $('#reportModal').modal('hide');
                    loadReports();
                    resetForm();
                },
                error: function() {
                    alert('Ошибка обновления');
                }
            });
        } else {
            // CREATE — ИСПРАВЬ done()
            $.post('/api/reports', $form.serialize())
                .done(function() {
                    $('#reportModal').modal('hide');
                    loadReports();
                    resetForm();  // ← ДОБАВЬ ЭТО!
                })
                .fail(function() {
                    alert('Ошибка сохранения');
                });
        }
    } else {
        alert('Заполните все поля');
    }
}


// ← ДОБАВЬ ЭТУ ФУНКЦИЮ
function resetForm() {
    $('#reportForm')[0].reset();
    $('#reportForm').removeData('report-id');
    $('.modal-title').text('Новый отчёт');
    $('.btn-primary[onclick="saveReport()"]').text('Сохранить');
}

    </script>
</x-app-layout>
