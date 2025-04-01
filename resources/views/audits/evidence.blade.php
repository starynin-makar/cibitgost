@extends('layouts.app')

@section('content')
<div class="container-custom">
    <div class="text-sm text-gray-600 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Главная</a>
        <span class="mx-2">/</span>
        <a href="{{ route('organizations.index') }}" class="hover:text-blue-600">Организации</a>
        <span class="mx-2">/</span>
        <a href="{{ route('organizations.audits.index', $organization) }}" class="hover:text-blue-600">Аудиты</a>
        <span class="mx-2">/</span>
        <span>Свидетельства</span>
    </div>

    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-900">Свидетельства аудита</h2>
            <a href="{{ url()->previous() }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Назад
            </a>
        </div>
        @if($norm)
            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                <p class="font-semibold">Норма: {{ $norm->code }}</p>
                <p class="mt-2">{{ $norm->description }}</p>
            </div>
        @endif
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="p-6">
            <form action="{{ route('audit.evidence.store', [$organization->id, $audit->id]) }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf
                
                @if($norm)
                    <input type="hidden" name="norm_id" value="{{ $norm->id }}">
                @else
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="norm_id">
                            Выберите норму
                        </label>
                        <select name="norm_id" 
                                id="norm_id" 
                                required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Выберите норму</option>
                            @foreach($audit->norms as $normOption)
                                <option value="{{ $normOption->id }}">
                                    {{ $normOption->code }} - {{ Str::limit($normOption->description, 100) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="file">
                        Файл
                    </label>
                    <input type="file" 
                           name="file" 
                           id="file"
                           required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                        Описание
                    </label>
                    <input type="text"
                           name="description"
                           id="description"
                           required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Загрузить
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Дата
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Норма
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Описание
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Файл
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Пользователь
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($evidences as $evidence)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $evidence->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $evidence->norm->code }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $evidence->description }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ Storage::url($evidence->file_path) }}" 
                               class="text-blue-600 hover:text-blue-900"
                               target="_blank">
                                Скачать
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $evidence->user->name }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 