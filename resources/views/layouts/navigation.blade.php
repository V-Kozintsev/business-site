<nav x-data="{ open: false }" class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="bi bi-building me-2"></i>Business Site
        </a>

        {{-- Navigation Links --}}
        <div class="navbar-nav ms-auto">
            @auth
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="/dashboard">
                    🏠 Главная
                </a>
                <a class="nav-link {{ request()->is('daily-reports') ? 'active' : '' }}" href="/daily-reports">
                    📊
 Отчёты
                </a>
                
                @if (auth()->user()?->hasRole('admin'))
                    <a class="nav-link {{ request()->is('admin-reports') ? 'active' : '' }}" href="/admin-reports">
                        👑 Админ
                    </a>
                    <a class="nav-link {{ request()->is('news') ? 'active' : '' }}" href="/news">
                        📰 Новости
                    </a>
                @endif
            @endauth
        </div>

        {{-- User info --}}
        @auth
            <span class="navbar-text me-3">
                {{ auth()->user()->name }} 
                @if (auth()->user()?->hasRole('admin'))
                    <span class="badge bg-danger">Admin</span>
                @endif
            </span>
            
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    🚪 Выход
                </button>
            </form>
        @else
            <div>
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm me-2">🔐 Войти</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">📝 Зарегистрироваться</a>
            </div>
        @endauth
    </div>
</nav>
