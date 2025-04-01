@extends('layouts.app')

@section('content')
<div class="container-custom">
    <!-- Хлебные крошки -->
    <div class="text-sm text-gray-600 mb-8">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Главная</a>
        <span class="mx-2">/</span>
        <span>Организации</span>
    </div>

    <!-- Заголовок и кнопка добавления -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Список организаций</h1>
        <button onclick="openAddModal()" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Добавить организацию
        </button>
    </div>

    <!-- Таблица организаций -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Номер
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Название
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Генеральный директор
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Статус
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Действия
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($organizations as $index => $organization)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $index + 1 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $organization->name }}</div>
                        <div class="text-sm text-gray-500">{{ $organization->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $organization->director ?? 'Не указан' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($organization->status === 'green')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Активный
                            </span>
                        @elseif($organization->status === 'orange')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                В процессе
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Неактивный
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button onclick="editOrganization({{ $organization->id }})"
                                class="text-blue-600 hover:text-blue-900">
                            Редактировать
                        </button>
                        <a href="{{ route('organizations.audits.index', $organization) }}" 
                           class="text-indigo-600 hover:text-indigo-900">
                            Аудиты
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Модальное окно -->
<div id="editModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <form id="organizationForm" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="organization_id" id="organization_id">

                <div class="space-y-6">
                    <div class="flex justify-between items-center">
                        <h3 id="modalTitle" class="text-lg font-medium text-gray-900">Редактировать организацию</h3>
                        <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Название организации *</label>
                            <input type="text" id="name" name="name" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="director" class="block text-sm font-medium text-gray-700">Генеральный директор *</label>
                            <input type="text" id="director" name="director" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Описание</label>
                            <textarea id="description" name="description" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Адрес *</label>
                            <input type="text" id="address" name="address" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Телефон *</label>
                            <input type="text" id="phone" name="phone" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                            <input type="email" id="email" name="email" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Отмена
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                        Сохранить
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Определяем функции в глобальной области видимости
window.openAddModal = function() {
    const form = document.getElementById('organizationForm');
    form.reset();
    form.action = '{{ route("organizations.store") }}';
    document.getElementById('modalTitle').textContent = 'Добавить организацию';
    document.getElementById('organization_id').value = '';
    document.querySelector('input[name="_method"]').value = 'POST';
    
    document.getElementById('editModal').classList.remove('hidden');
}

window.closeModal = function() {
    document.getElementById('editModal').classList.add('hidden');
}

window.editOrganization = function(organizationId) {
    fetch(`/organizations/${organizationId}/edit`)
        .then(response => response.json())
        .then(organization => {
            const form = document.getElementById('organizationForm');
            form.action = `/organizations/${organizationId}`;
            document.querySelector('input[name="_method"]').value = 'PUT';
            document.getElementById('modalTitle').textContent = 'Редактировать организацию';
            
            // Заполняем поля формы данными организации
            document.getElementById('organization_id').value = organization.id;
            document.getElementById('name').value = organization.name;
            document.getElementById('director').value = organization.director;
            document.getElementById('description').value = organization.description || '';
            document.getElementById('address').value = organization.address;
            document.getElementById('phone').value = organization.phone;
            document.getElementById('email').value = organization.email;
            
            // Показываем модальное окно
            document.getElementById('editModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ошибка при загрузке данных организации');
        });
}

// Обработчик отправки формы
document.getElementById('organizationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Произошла ошибка при сохранении организации');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Произошла ошибка при сохранении организации');
    });
});
</script>
@endpush 