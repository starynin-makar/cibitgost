@extends('layouts.app')

@section('content')
<div class="container-custom">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Редактировать доступ</h2>

        <form action="{{ route('access.update', $access) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Выберите пользователя
                </label>
                <select name="user_id" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $access->user_id == $user->id ? 'selected' : '' }}>
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
                                   data-organization-id="{{ $organization->id }}"
                                   {{ in_array($organization->id, $selectedOrganizations) ? 'checked' : '' }}>
                            <span class="ml-2">{{ $organization->name }}</span>
                        </label>
                        
                        <div class="ml-6 mt-2 audits-container {{ in_array($organization->id, $selectedOrganizations) ? '' : 'hidden' }}" 
                             id="audits-{{ $organization->id }}">
                            @foreach($organization->audits as $audit)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" 
                                           name="audits[{{ $organization->id }}][]" 
                                           value="{{ $audit->id }}" 
                                           class="form-checkbox"
                                           {{ isset($selectedAudits[$organization->id]) && in_array($audit->id, $selectedAudits[$organization->id]) ? 'checked' : '' }}>
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
document.querySelectorAll('.organization-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const organizationId = this.dataset.organizationId;
        const auditsContainer = document.getElementById(`audits-${organizationId}`);
        
        if (this.checked) {
            auditsContainer.classList.remove('hidden');
        } else {
            auditsContainer.classList.add('hidden');
            auditsContainer.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
            });
        }
    });
});
</script>
@endpush
@endsection 