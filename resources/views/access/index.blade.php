@extends('layouts.app')

@section('content')
<div class="container-custom">
    <!-- Хлебные крошки -->
    <div class="text-sm text-gray-600 mb-8">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Главная</a>
        <span class="mx-2">/</span>
        <span>Управление доступами</span>
    </div>

    <!-- Заголовок и кнопки -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Управление доступами</h1>
        <div class="flex space-x-3">
            <a href="{{ route('access.create') }}" 
               class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                Добавить доступ
            </a>
        </div>
    </div>

    <!-- Сообщения об успехе/ошибке -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Таблица всех доступов -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Все доступы</h2>
            <p class="mt-1 text-sm text-gray-500">Список всех выданных доступов к организациям и аудитам</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Пользователь</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Организации и аудиты</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($groupedAccesses as $userData)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($userData['user'])
                                    <a href="{{ route('access.edit', $userData['accesses']->first()['id']) }}" 
                                       class="hover:text-blue-600 cursor-pointer">
                                        {{ $userData['user']->name }} <br>
                                        <span class="text-gray-500">{{ $userData['user']->email }}</span>
                                    </a>
                                @else
                                    <span class="text-red-600">Пользователь не найден</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="space-y-2">
                                    @foreach($userData['accesses'] as $access)
                                        <div class="border-b border-gray-100 pb-2 last:border-0 last:pb-0 flex justify-between items-start group">
                                            <div>
                                                <div class="font-medium">{{ $access['organization'] }}</div>
                                                @if($access['audits'])
                                                    <div class="text-gray-500 text-sm mt-1">
                                                        Аудиты: {{ $access['audits'] }}
                                                    </div>
                                                @endif
                                            </div>
                                            <form action="{{ route('access.destroy', $access['id']) }}" 
                                                  method="POST" 
                                                  class="inline-block opacity-0 group-hover:opacity-100 transition-opacity">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-gray-400 hover:text-red-600 transition-colors" 
                                                        onclick="return confirm('Вы уверены, что хотите удалить доступ к {{ $access['organization'] }}?')">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('access.edit', $userData['accesses']->first()['id']) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    Редактировать
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Доступы не найдены
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 