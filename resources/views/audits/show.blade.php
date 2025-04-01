@extends('layouts.app')

@section('content')
<div class="container-custom">
    <!-- Хлебные крошки -->
    <div class="text-sm text-gray-600 mb-8">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Главная</a>
        <span class="mx-2">/</span>
        <a href="{{ route('organizations.index') }}" class="hover:text-blue-600">Организации</a>
        <span class="mx-2">/</span>
        <a href="{{ route('organizations.audits.index', $audit->organization) }}" class="hover:text-blue-600">Аудиты</a>
        <span class="mx-2">/</span>
        <span>{{ $audit->title }}</span>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">
                {{ $audit->title }}
            </h1>
            <p class="text-gray-600">{{ $audit->organization->name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('organizations.audits.index', $audit->organization) }}" 
               class="btn-secondary">
                Назад к списку
            </a>
            <a href="{{ route('organizations.audits.conduct', ['organization' => $organization, 'audit' => $audit]) }}" 
               class="btn-primary">
                Перейти к оценке
            </a>
            @if(auth()->user()->is_admin || $audit->user_id === auth()->id())
            <a href="{{ route('organizations.audits.edit', ['organization' => $audit->organization, 'audit' => $audit]) }}" 
               class="btn-blue">
                Редактировать
            </a>
            <form action="{{ route('organizations.audits.destroy', ['organization' => $audit->organization, 'audit' => $audit]) }}" 
                  method="POST" 
                  class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="btn-red"
                        onclick="return confirm('Вы уверены?')">
                    Удалить
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Информация об аудите
            </h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Описание</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $audit->description ?? 'Не указано' }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Статус</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $audit->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $audit->status === 'completed' ? 'Завершен' : 'В процессе' }}
                        </span>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Дата начала</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ Carbon\Carbon::parse($audit->start_date)->format('d.m.Y') }}
                    </dd>
                </div>
                @if($audit->end_date)
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Дата завершения</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ Carbon\Carbon::parse($audit->end_date)->format('d.m.Y') }}
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>
</div>
@endsection 