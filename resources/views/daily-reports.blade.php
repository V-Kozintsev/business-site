<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Отчёты менеджера
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#reportModal">
                        Новый отчёт
                    </button>
                    <table class="table table-striped mt-3 w-full" id="reportsTable">
                        <thead>
                            <tr>
                                <th>Дата</th>
                                <th>Адрес точки</th>
                                <th>Выручка</th>
                                <th>Действия</th> 
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <!-- Modal -->
                    <div class="modal fade" id="reportModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Новый отчёт</h5>
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
                                            <input type="number" step="0.01" class="form-control" name="revenue" required>
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
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
        new bootstrap.Modal(document.getElementById('reportModal')).show();
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
        $('#reportsTable tbody').empty();
        data.forEach(report => {
            $('#reportsTable tbody').append(`
                <tr>
                    <td>${report.report_date}</td>
                    <td>${report.sales_point}</td>
                    <td>${report.revenue} руб.</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" 
                                data-id="${report.id}"
                                data-sales="${report.sales_point}"
                                data-revenue="${report.revenue}"
                                data-date="${report.report_date}">
                            Изменить
                        </button>
                         <button class="btn btn-sm btn-danger delete-btn me-1" data-id="${report.id}" style="background-color: #dc3545; color: white;">
    Удалить
</button>
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
