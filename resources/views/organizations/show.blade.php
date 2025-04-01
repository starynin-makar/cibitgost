@extends('layouts.app')

@section('content')
<div class="container-custom">
    <div class="breadcrumbs">
        <a href="{{ route('dashboard') }}">Главная</a> > 
        <a href="{{ route('organizations.index') }}">Организации</a> > 
        <span>{{ $organization->name }}</span>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">{{ $organization->name }}</h1>
            <div class="flex gap-4">
                <a href="{{ route('organizations.audits.create', $organization) }}" 
                   class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Добавить аудит
                </a>
                <a href="{{ route('organizations.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Назад
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-semibold mb-4">Информация об организации</h2>
                <div class="space-y-3">
                    <p><span class="font-semibold">Адрес:</span> {{ $organization->address }}</p>
                    <p><span class="font-semibold">Телефон:</span> {{ $organization->phone }}</p>
                    <p><span class="font-semibold">Email:</span> {{ $organization->email }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 