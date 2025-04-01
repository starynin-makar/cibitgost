<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CIBIT-57580</title>
    <!-- Сначала подключаем jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Затем подключаем Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .container-custom {
            max-width: 1200px !important;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1a56db;
            background: linear-gradient(45deg, #1a56db, #3182ce);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }
        .logo:hover {
            transform: scale(1.05);
        }
        .nav-link {
            color: #2d3748;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #1a56db;
        }
        /* Стили для модального окна */
        .modal {
            border-radius: 8px;
        }
        .modal .modal-content {
            padding: 20px;
            border-radius: 5px;
        }
        .modal .input-field input:focus {
            border-bottom: 1px solid #007bff;
            box-shadow: 0 1px 0 0 #007bff;
        }
        .modal .input-field input:focus + label {
            color: #007bff;
        }
        .modal .modal-footer {
            padding: 4px 24px;
        }
        .modal .btn-flat:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div id="app">
        <!-- Навигационная панель -->
        <nav class="bg-white shadow-sm">
            <div class="container-custom">
                <div class="flex justify-between h-12">
                    <div class="flex">
                        <!-- Логотип -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ auth()->check() ? route('organizations.index') : '/' }}" class="logo">
                                CIBIT-57580
                            </a>
                        </div>

                        <!-- Основное меню -->
                        <div class="hidden md:ml-6 md:flex md:space-x-8">
                            @auth
                                <a href="{{ route('organizations.index') }}" 
                                   class="nav-link inline-flex items-center px-1 pt-1">
                                    Организации
                                </a>
                                <a href="{{ route('norms.index') }}" 
                                   class="nav-link inline-flex items-center px-1 pt-1">
                                    Нормы
                                </a>
                                <a href="{{ route('access.index') }}" 
                                   class="nav-link inline-flex items-center px-1 pt-1">
                                    Доступы
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Правая часть навигации -->
                    <div class="flex items-center">
                        @auth
                            <div class="flex items-center space-x-4">
                                <span class="text-gray-700">{{ Auth::user()->name }}</span>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" 
                                            class="text-gray-500 hover:text-blue-700 transition duration-150">
                                        Выйти
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" 
                               class="nav-link mr-4">
                                Войти
                            </a>
                            <a href="{{ route('register') }}" 
                               class="nav-link">
                                Регистрация
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Основной контент -->
        <main class="py-2">
            @yield('content')
        </main>

        <!-- Модальное окно только для страниц, где оно нужно -->
        @if(request()->segment(3) === 'conduct' || request()->segment(3) === 'measure')
            @include('partials.modals.evidence')
        @endif
    </div>

    @stack('scripts')
</body>
</html> 