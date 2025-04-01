@extends('layouts.print')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Основной контейнер -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <!-- Заголовок -->
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-semibold text-gray-900 text-center">
                    Оценка соответствия требованиям по защите информации
                </h1>
                <p class="mt-2 text-sm text-gray-600 text-center">
                    Дата формирования: {{ now()->format('d.m.Y') }}
                </p>
            </div>

            <!-- Процессы -->
            @foreach($groupedNorms as $processName => $subprocessGroups)
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-medium text-gray-900 mb-4">{{ $processName }}</h2>

                    @foreach($subprocessGroups as $subprocessName => $norms)
                        <div class="mb-8 last:mb-0">
                            <h3 class="text-lg font-medium text-gray-800 mb-4">
                                {{ $subprocessName }}
                            </h3>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Код меры
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Описание меры
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                                Оценка
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($norms as $norm)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $norm->code }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    {{ $norm->description }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $assessment = $norm->assessments->first();
                                                        $score = $assessment ? $assessment->score : null;
                                                        $showHalfScore = in_array($tab, [9, 10, 11, 12, 13]); // B10, B11, B12, B13, ЖЦ АС
                                                        $isNziTab = $tab == 14; // НЗИ (Z)
                                                    @endphp
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $score === null ? 'bg-gray-100 text-gray-800' : 
                                                           ($isNziTab ? 
                                                              ($score == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') : 
                                                              ($score === 'н/о' ? 'bg-gray-100 text-gray-800' :
                                                                 ($showHalfScore && $score == 0.5 ? 'bg-yellow-100 text-yellow-800' :
                                                                    ($score >= 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')))) 
                                                        }}">
                                                        {{ $score ?? '-' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach

                    <!-- Оценки процесса -->
                    @if(isset($scores[$processName]))
                        <div class="mt-6 bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Итоговые оценки процесса:</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Качественная оценка:</span>
                                    <span class="ml-2 font-medium">{{ $scores[$processName]['qualitative'] }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Числовая оценка:</span>
                                    <span class="ml-2 font-medium">{{ $scores[$processName]['numerical'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach

            <!-- Подписи -->
            <div class="p-6 bg-gray-50">
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-4">
                            Руководитель проверяющей группы:
                        </p>
                        <div class="flex items-center space-x-4">
                            <div class="flex-grow h-0.5 border-t-2 border-gray-300"></div>
                            <span class="text-sm text-gray-500">(подпись)</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-4">
                            Представитель проверяемой организации:
                        </p>
                        <div class="flex items-center space-x-4">
                            <div class="flex-grow h-0.5 border-t-2 border-gray-300"></div>
                            <span class="text-sm text-gray-500">(подпись)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body {
            background-color: white;
        }
        
        .shadow-sm {
            box-shadow: none;
        }
        
        .bg-gray-100 {
            background-color: white;
        }
        
        table {
            border-collapse: collapse;
        }
        
        td, th {
            border: 1px solid #e5e7eb;
        }
        
        tr {
            page-break-inside: avoid;
        }
        
        thead {
            display: table-header-group;
        }
        
        .max-w-7xl {
            max-width: none;
        }
        
        .sm\:px-6,
        .lg\:px-8 {
            padding-left: 0;
            padding-right: 0;
        }
    }
</style>
@endsection 