@extends('layouts.app')

@section('content')
<div class="container-custom">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Добавить доступ</h2>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('access.store') }}" method="POST">
            @csrf
            
            <!-- Создание нового пользователя -->
            <div class="mb-6 new-user-fields {{ old('user_id') ? 'hidden' : '' }}">
                <h3 class="text-lg font-semibold mb-4">Создать нового пользователя</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                            Имя пользователя
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                            Email
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                            Пароль
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Или выбрать существующего</h3>
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Выберите пользователя
                </label>
                <select name="user_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Выберите пользователя</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Выберите организации
                </label>
                @foreach($organizations as $organization)
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" 
                                   name="organizations[]" 
                                   value="{{ $organization->id }}" 
                                   class="form-checkbox organization-checkbox"
                                   data-organization-id="{{ $organization->id }}">
                            <span class="ml-2">{{ $organization->name }}</span>
                        </label>
                        
                        <div class="ml-6 mt-2 audits-container hidden" id="audits-{{ $organization->id }}">
                            @foreach($organization->audits as $audit)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" 
                                           name="audits[{{ $organization->id }}][]" 
                                           value="{{ $audit->id }}" 
                                           class="form-checkbox">
                                    <span class="ml-2">{{ $audit->title }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('access.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Отмена
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Сохранить
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Инициализация Select2 для выбора пользователя
    $('select[name="user_id"]').select2({
        placeholder: 'Выберите пользователя',
        allowClear: true
    });

    // Обработчик для чекбоксов организаций
    $('.organization-checkbox').on('change', function() {
        const organizationId = $(this).data('organization-id');
        const auditsContainer = $(`#audits-${organizationId}`);
        
        if (this.checked) {
            auditsContainer.removeClass('hidden');
        } else {
            auditsContainer.addClass('hidden');
            auditsContainer.find('input[type="checkbox"]').prop('checked', false);
        }
    });

    // Если выбран существующий пользователь, скрываем поля создания нового
    $('select[name="user_id"]').on('change', function() {
        const newUserFields = $('.new-user-fields');
        if ($(this).val()) {
            newUserFields.addClass('hidden');
        } else {
            newUserFields.removeClass('hidden');
        }
    });
});
</script>
@endpush
@endsection 