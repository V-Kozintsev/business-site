@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-8">
    <!-- Заголовок -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="display-5 fw-bold mb-0">📰 Новости компании</h1>
        @if(auth()->check() && auth()->user()->is_admin)
            <button class="btn btn-primary btn-lg shadow" data-bs-toggle="modal" data-bs-target="#newsModal">
                <i class="bi bi-plus-circle me-2"></i>Новая новость
            </button>
        @endif
    </div>

    <!-- Список новостей -->
    <div class="row g-4" id="newsContainer">
        <div class="col-12">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Загрузка...</span>
                </div>
                <p class="mt-3 text-muted">Загружаем новости...</p>
            </div>
        </div>
    </div>
</div>

<!-- МОДАЛКА НОВОСТИ -->
<div class="modal fade" id="newsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title fw-bold" id="newsModalTitle">➕ Новая новость</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newsForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Заголовок <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" required maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Текст новости <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="content" rows="6" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" onclick="saveNews()">
                    <i class="bi bi-check-circle me-2"></i>Опубликовать
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // ✅ КРИТИЧНО: CSRF токен для ВСЕХ Ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    });
    
    // ✅ Проверка токена в консоли
    console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
    
    loadNews();
});

// Загрузка при открытии
$(()=>loadNews());

function loadNews() {
    $.get('/api/news').done(news=>{
        const $container = $('#newsContainer');
        if(news.length === 0) {
            $container.html(`
                <div class="col-12">
                    <div class="text-center py-8">
                        <i class="bi bi-newspaper display-1 text-muted mb-4"></i>
                        <h4 class="text-muted mb-3">Нет новостей</h4>
                        <p class="text-muted">Будьте первым, кто опубликует новость!</p>
                    </div>
                </div>
            `);
            return;
        }
        
        $container.html(
            news.map(n=>`
                <div class="col-lg-6 col-xl-4">
                    <div class="card shadow-sm h-100 transition-all hover-shadow-lg">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">${n.title}</h5>
                            <p class="card-text text-muted">${n.content}</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-black-50">
                                    <i class="bi bi-clock me-1"></i>
                                    ${new Date(n.created_at).toLocaleDateString('ru-RU')}
                                </small>
                                ${window.isAdmin ? `
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-warning edit-news" 
                                                data-id="${n.id}" data-title="${n.title}" data-content="${n.content}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger delete-news" data-id="${n.id}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('')
        );
    }).fail(()=>{
        $('#newsContainer').html(`
            <div class="col-12">
                <div class="alert alert-danger text-center">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Ошибка загрузки новостей
                </div>
            </div>
        `);
    });
}

function saveNews() {
    const $form = $('#newsForm');
    if(!$form[0].checkValidity()) {
        $form[0].reportValidity();
        return;
    }
    
    const isEdit = $form.data('news-id');
    const url = isEdit ? `/api/news/${isEdit}` : '/api/news';
    
     $.ajax({
        method: isEdit ? 'PUT' : 'POST',
        url: url,
        data: $form.serialize(),  // ← title=...&content=...
        dataType: 'json',
        success: ()=>{
            $('#newsModal').modal('hide');
            setTimeout(() => {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('padding-right', '');
            }, 300);
            loadNews();
            resetNewsForm();
        },
        error: (xhr)=>{
            console.error('Save error:', xhr.status, xhr.responseJSON);
            alert('Ошибка сохранения: ' + (xhr.responseJSON?.message || xhr.status));
        }
    });
}

function resetNewsForm() {
    $('#newsForm')[0].reset();
    $('#newsForm').removeData();
    $('#newsModalTitle').text('➕ Новая новость');
}

// Edit/Delete handlers
$(document).on('click', '.edit-news', function() {
    const id = $(this).data('id');
    $('#newsForm').data('news-id', id);
    $('input[name="title"]').val($(this).data('title'));
    $('textarea[name="content"]').val($(this).data('content'));
    $('#newsModalTitle').text('✏️ Редактировать новость');
    $('#newsModal').modal('show');
});

$(document).on('click', '.delete-news', function() {
    if(confirm('Удалить эту новость?')) {
        $.ajax({
            method: 'DELETE',
            url: `/api/news/${$(this).data('id')}`,
            data: { _token: $('meta[name="csrf-token"]').attr('content') },  // ← КРИТИЧНО!
            success: loadNews,
            error: (xhr)=>{
                console.error('Delete error:', xhr.status);
                alert('Ошибка удаления');
            }
        });
    }
});

// Admin check
window.isAdmin = {{ auth()->check() && auth()->user()->is_admin ? 'true' : 'false' }};
</script>
@endsection
