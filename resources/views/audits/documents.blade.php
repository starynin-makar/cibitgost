@extends('layouts.app')

@section('content')
<div class="container-custom">
    <!-- Хлебные крошки -->
    <div class="text-sm text-gray-600 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Главная</a>
        <span class="mx-2">/</span>
        <a href="{{ route('organizations.index') }}" class="hover:text-blue-600">Организации</a>
        <span class="mx-2">/</span>
        <a href="{{ route('organizations.audits.index', $audit->organization) }}" class="hover:text-blue-600">Аудиты</a>
        <span class="mx-2">/</span>
        <span>Документы аудита</span>
    </div>

    <!-- Заголовок -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Документы аудита</h1>
        <p class="mt-1 text-sm text-gray-600">{{ $audit->organization->name }}</p>
    </div>

    <!-- Таблица с документами -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Норма</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Документ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Сотрудник</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Тип источника</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($documents as $document)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $document->norm->code }}</td>
                    <td class="px-6 py-4">{{ $document->file_name ?? 'Нет файла' }}</td>
                    <td class="px-6 py-4">{{ $document->employee_name }}</td>
                    <td class="px-6 py-4">{{ $document->source_type }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($document->file_path)
                        <a href="{{ asset('storage/' . $document->file_path) }}" 
                           class="text-blue-600 hover:text-blue-900" 
                           target="_blank">Скачать</a>
                        @else
                        <span class="text-gray-400">Нет файла</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                
                @if(count($documents) == 0)
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Документы не найдены
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Кнопка возврата -->
    <div class="mt-6">
        <a href="{{ route('audit.conduct', ['audit' => $audit->id, 'process' => $process, 'tab' => $tab]) }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
            Вернуться к аудиту
        </a>
    </div>
</div>
@endsection 