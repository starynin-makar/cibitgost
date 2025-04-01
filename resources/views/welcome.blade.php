@extends('layouts.app')

@section('content')
<div class="container-custom">
    <div class="min-h-screen flex flex-col items-center justify-center">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                Добро пожаловать в ГОСТ Аудит
            </h1>
            <p class="text-xl text-gray-600 mb-8">
                Система управления аудитами и проверками организаций
            </p>
            <div class="space-x-4">
                <a href="{{ route('login') }}" 
                   class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition duration-200">
                    Войти в систему
                </a>
                <a href="{{ route('register') }}" 
                   class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-200">
                    Зарегистрироваться
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
