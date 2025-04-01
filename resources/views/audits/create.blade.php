@extends('layouts.app')

@section('content')
<div class="container-custom">
    <div class="breadcrumbs">
        <a href="{{ route('dashboard') }}">Главная</a> > 
        <a href="{{ route('organizations.index') }}">Организации</a> > 
        <a href="{{ route('organizations.audits.index', $organization) }}">Аудиты</a> > 
        <span>{{ isset($audit) ? 'Редактирование' : 'Создание' }} аудита</span>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">{{ isset($audit) ? 'Редактировать' : 'Добавить' }} аудит для {{ $organization->name }}</h1>

        <form action="{{ isset($audit) ? route('organizations.audits.update', [$organization, $audit]) : route('organizations.audits.store', $organization) }}" 
              method="POST">
            @csrf
            @if(isset($audit))
                @method('PUT')
            @endif

            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                        Название аудита
                    </label>
                    <input type="text" name="title" id="title" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           value="{{ old('title', $audit->title ?? '') }}" required>
                    @error('title')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                        Описание
                    </label>
                    <textarea name="description" id="description" rows="4"
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description', $audit->description ?? '') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                @if(isset($audit))
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                        Статус
                    </label>
                    <select name="status" id="status" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="pending" {{ $audit->status === 'pending' ? 'selected' : '' }}>В процессе</option>
                        <option value="completed" {{ $audit->status === 'completed' ? 'selected' : '' }}>Завершен</option>
                    </select>
                </div>
                @endif
            </div>

            <div class="flex items-center justify-between mt-6">
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    {{ isset($audit) ? 'Сохранить' : 'Создать' }}
                </button>
                <a href="{{ route('organizations.audits.index', $organization) }}" 
                   class="text-blue-500 hover:text-blue-700">
                    Отмена
                </a>
            </div>
        </form>
    </div>
</div>
@endsection 