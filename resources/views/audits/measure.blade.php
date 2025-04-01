@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800">{{ $norm->code }}</h2>
            <p class="mt-2 text-gray-600">{{ $norm->description }}</p>
        </div>

        <form action="{{ route('organizations.audits.assessments.update', ['organization' => $organization->id, 'audit' => $audit->id]) }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="norm_id" value="{{ $norm->id }}">
            
            <div>
                <label for="evidence" class="block text-sm font-medium text-gray-700">Свидетельство</label>
                <textarea id="evidence" name="evidence" rows="4" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    required>{{ $assessment->evidence ?? '' }}</textarea>
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700">Примечания</label>
                <textarea id="notes" name="notes" rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >{{ $assessment->notes ?? '' }}</textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ url()->previous() }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Отмена
                </a>
                <button type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Сохранить
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 