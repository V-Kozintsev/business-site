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
        loadReports();
    });

    function loadReports() {
        $.get('/api/reports', function(data) {
            $('#reportsTable tbody').empty();
            data.forEach(report => {
                $('#reportsTable tbody').append(`
                    <tr>
                        <td>${report.report_date}</td>
                        <td>${report.sales_point}</td>
                        <td>${report.revenue} руб.</td>
                    </tr>
                `);
            });
        }).fail(function() {
            console.log('API error');
        });
    }

    function saveReport() {
        if ($('#reportForm')[0].checkValidity()) {
            $.post('/api/reports', $('#reportForm').serialize())
                .done(function() {
                    $('#reportModal').modal('hide');
                    loadReports();
                })
                .fail(function() {
                    alert('Ошибка сохранения');
                });
        } else {
            alert('Заполните все поля');
        }
    }
    </script>
</x-app-layout>
