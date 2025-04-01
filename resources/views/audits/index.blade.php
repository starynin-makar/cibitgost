@extends('layouts.app')

@section('content')
<div class="container-custom">
    <!-- Хлебные крошки -->
    <div class="text-sm text-gray-600 mb-8">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Главная</a>
        <span class="mx-2">/</span>
        <a href="{{ route('organizations.index') }}" class="hover:text-blue-600">Организации</a>
        <span class="mx-2">/</span>
        <span>Аудиты</span>
    </div>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">
            Аудиты организации "{{ $organization->name }}"
        </h1>
        <div class="flex gap-2">
            <a href="{{ route('organizations.index') }}" 
               class="btn-secondary">
                Назад
            </a>
            @if(auth()->user()->is_admin)
            <button type="button" 
                    onclick="openCreateAuditModal()"
                    class="btn-primary">
                Создать аудит
            </button>
            @endif
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Название
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Статус
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Дата создания
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Действия
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($audits as $audit)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            <a href="{{ route('organizations.audits.conduct', ['organization' => $organization->id, 'audit' => $audit->id]) }}" 
                               class="hover:text-blue-600">
                                {{ $audit->title }}
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $audit->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $audit->status === 'completed' ? 'Завершен' : 'В процессе' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $audit->created_at->format('d.m.Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-3">
                            <!-- Кнопка проведения аудита -->
                            <a href="{{ route('organizations.audits.conduct', ['organization' => $organization->id, 'audit' => $audit->id]) }}" 
                               class="text-blue-600 hover:text-blue-900" 
                               title="Провести аудит">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </a>
                            @if(auth()->user()->is_admin || $audit->user_id === auth()->id())
                            <!-- Кнопка редактирования -->
                            <a href="{{ route('organizations.audits.edit', ['organization' => $organization->id, 'audit' => $audit->id]) }}"
                               class="text-yellow-600 hover:text-yellow-900"
                               title="Редактировать">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <!-- Кнопка удаления -->
                            <form action="{{ route('organizations.audits.destroy', ['organization' => $organization->id, 'audit' => $audit->id]) }}" 
                                  method="POST" 
                                  class="inline-block"
                                  onsubmit="return confirm('Вы уверены, что хотите удалить этот аудит?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900"
                                        title="Удалить">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                            <!-- Кнопка копирования аудита -->
                            <button onclick="openCopyModal({{ $audit->id }})" 
                                    class="text-indigo-600 hover:text-indigo-900"
                                    title="Копировать аудит">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                </svg>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                        Аудиты не найдены
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Модальное окно создания аудита -->
<div id="createAuditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Создание аудита</h3>
            <form id="auditForm" action="{{ route('organizations.audits.store', ['organization' => $organization->id]) }}" method="POST" class="mt-4">
                @csrf
                <div id="validation-errors" class="mb-4 hidden">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <ul class="list-disc list-inside"></ul>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Название</label>
                    <input type="text" name="title" id="title" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Описание</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                </div>
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Статус</label>
                    <select name="status" id="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="planned">Запланирован</option>
                        <option value="in_progress">В процессе</option>
                        <option value="completed">Завершен</option>
                        <option value="cancelled">Отменен</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeCreateAuditModal()" 
                            class="btn-secondary">
                        Отмена
                    </button>
                    <button type="submit" class="btn-primary">
                        Создать
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Модальное окно редактирования аудита -->
<div id="editAuditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Редактирование аудита</h3>
            <form id="editAuditForm" method="POST" class="mt-4">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="edit_title" class="block text-sm font-medium text-gray-700">Название</label>
                    <input type="text" name="title" id="edit_title" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div class="mb-4">
                    <label for="edit_description" class="block text-sm font-medium text-gray-700">Описание</label>
                    <textarea name="description" id="edit_description" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                </div>
                <div class="mb-4">
                    <label for="edit_status" class="block text-sm font-medium text-gray-700">Статус</label>
                    <select name="status" id="edit_status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="planned">Запланирован</option>
                        <option value="in_progress">В процессе</option>
                        <option value="completed">Завершен</option>
                        <option value="cancelled">Отменен</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeEditAuditModal()" 
                            class="btn-secondary">
                        Отмена
                    </button>
                    <button type="submit" class="btn-primary">
                        Сохранить
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Модальное окно для копирования аудита -->
<div id="copyAuditModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Копирование аудита</h2>
            
            <form id="copyAuditForm" method="GET">
                <input type="hidden" id="copyAuditId" name="audit_id">
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="copy_assessments" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Копировать оценки</span>
                    </label>
                </div>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="copy_documents" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Копировать документы</span>
                    </label>
                </div>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="copy_evidences" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Копировать свидетельства</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeCopyModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Отмена
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                        Копировать
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#auditForm').on('submit', function(e) {
        e.preventDefault();
        
        // Логируем данные формы перед отправкой
        console.log('Form data:', {
            title: $('#title').val(),
            description: $('#description').val(),
            status: $('#status').val()
        });

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                // Закрываем модальное окно
                $('#createAuditModal').addClass('hidden');
                
                // Перезагружаем страницу
                window.location.reload();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorList = $('#validation-errors ul');
                    errorList.empty();
                    
                    Object.keys(errors).forEach(function(key) {
                        errorList.append(`<li>${errors[key][0]}</li>`);
                    });
                    
                    $('#validation-errors').removeClass('hidden');
                } else {
                    alert('Произошла ошибка при создании аудита');
                }
                console.error('Error:', xhr);
            }
        });
    });
});

function openCreateAuditModal() {
    document.getElementById('createAuditModal').classList.remove('hidden');
}

function closeCreateAuditModal() {
    document.getElementById('createAuditModal').classList.add('hidden');
}

function openEditAuditModal(auditId) {
    fetch(`/organizations/{{ $organization->id }}/audits/${auditId}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('edit_title').value = data.title;
        document.getElementById('edit_description').value = data.description;
        document.getElementById('edit_status').value = data.status;
        
        const form = document.getElementById('editAuditForm');
        form.action = `/organizations/{{ $organization->id }}/audits/${auditId}`;
        
        document.getElementById('editAuditModal').classList.remove('hidden');
    })
    .catch(error => console.error('Error:', error));
}

function closeEditAuditModal() {
    document.getElementById('editAuditModal').classList.add('hidden');
}

function openCopyModal(auditId) {
    document.getElementById('copyAuditId').value = auditId;
    document.getElementById('copyAuditForm').action = '/audit/' + auditId + '/copy';
    document.getElementById('copyAuditModal').classList.remove('hidden');
}

function closeCopyModal() {
    document.getElementById('copyAuditModal').classList.add('hidden');
    document.getElementById('copyAuditForm').reset();
}
</script>
@endpush

@endsection