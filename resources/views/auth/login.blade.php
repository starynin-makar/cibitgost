@extends('layouts.app')

@section('content')
<div class="container-custom">
    <div class="min-h-[calc(100vh-8rem)] flex flex-col items-center justify-center">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="logo text-3xl">CIBIT-57580</h1>
                <p class="mt-2 text-gray-600">Вход в систему</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-8">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Пароль</label>
                        <input type="password" name="password" id="password"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               required>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember"
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Запомнить меня</label>
                    </div>

                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Войти
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:text-blue-500">
                        Нет аккаунта? Зарегистрируйтесь
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 