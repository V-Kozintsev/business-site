<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="bi bi-building me-2"></i>Business Site
        </a>

        {{-- Navigation Links --}}
        <div class="navbar-nav ms-auto">
            @auth
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="/dashboard">
                    Главная
                </a>
                <a class="nav-link {{ request()->is('daily-reports') ? 'active' : '' }}" href="/daily-reports">
                    Отчёты
                </a>
                
                {{-- ✅ НОВОСТИ ДЛЯ ВСЕХ --}}
                <a class="nav-link {{ request()->is('news') ? 'active' : '' }}" href="/news">
                    Новости
                </a>
                
                {{-- ✅ АДМИН ТОЛЬКО --}}
                @if (auth()->user()?->hasRole('admin'))
                    <a class="nav-link {{ request()->is('admin-reports') ? 'active' : '' }}" href="/admin-reports">
                        Все отчёты
                    </a>
                @endif
            @endauth
        </div>

        {{-- ✅ ПРОФИЛЬ/ГОСТЬ --}}
        {{-- ✅ ПРОФИЛЬ/ГОСТЬ --}}
@auth
    <div class="dropdown ms-3">
        <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center" 
                type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" 
                 style="width: 32px; height: 32px; font-size: 0.8rem;">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <span class="d-none d-md-inline fw-bold">{{ auth()->user()->name }}</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-1" aria-labelledby="userDropdown">
            <li><a class="dropdown-item fw-semibold py-2" href="/profile">
                <i class="bi bi-gear me-2 text-primary"></i>Профиль
            </a></li>
            <li><hr class="dropdown-divider my-1"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}" class="px-2">
                    @csrf
                    <button type="submit" class="dropdown-item fw-semibold text-danger py-2 w-100 text-start border-0 bg-transparent">
                        <i class="bi bi-box-arrow-right me-2"></i>Выход
                    </button>
                </form>
            </li>
        </ul>
    </div>
@else
    <div>
        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm me-2">🔐 Войти</a>
        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">📝 Зарегистрироваться</a>
    </div>
@endauth

    </div>
</nav>
