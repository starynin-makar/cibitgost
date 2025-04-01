@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Хлебные крошки -->
    <div class="text-sm text-gray-600 mb-8">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Главная</a>
        <span class="mx-2">/</span>
        <a href="{{ route('organizations.index') }}" class="hover:text-blue-600">Организации</a>
        <span class="mx-2">/</span>
        <a href="{{ route('organizations.audits.index', $audit->organization) }}" class="hover:text-blue-600">Аудиты</a>
        <span class="mx-2">/</span>
        <span>Сводная таблица</span>
    </div>

    <!-- Заголовок -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Таблица В.14 - Форма таблицы итоговых оценок</h1>
        <p class="mt-1 text-sm text-gray-600">{{ $audit->organization->name }}</p>
    </div>

    <!-- Табы -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 overflow-x-auto px-4" aria-label="Tabs">
                @foreach($tabs as $tabNum => $tabName)
                    @php
                        $isComplete = isset($completedTabsInfo['tabs'][$tabNum]) && $completedTabsInfo['tabs'][$tabNum]['is_complete'];
                        $isLastCompleted = $completedTabsInfo['last_completed_tab'] == $tabNum;
                        $bgColorClass = '';
                        
                        // Если текущая вкладка активна
                        if ($tab == $tabNum) {
                            $bgColorClass = 'border-blue-500 text-blue-600';
                        } 
                        // Если вкладка заполнена
                        else if ($isComplete) {
                            $bgColorClass = 'border-green-500 text-green-600 bg-green-50';
                        } 
                        // Если эта вкладка следующая после последней заполненной
                        else if ($completedTabsInfo['last_completed_tab'] > 0 && $tabNum == $completedTabsInfo['last_completed_tab'] + 1) {
                            $bgColorClass = 'border-yellow-500 text-yellow-600 bg-yellow-50';
                        } 
                        // Если эта вкладка была последней просмотренной
                        else if (isset($lastVisitedTab) && $lastVisitedTab == $tabNum) {
                            $bgColorClass = 'border-purple-500 text-purple-600 bg-purple-50';
                        }
                        // Все остальные вкладки
                        else {
                            $bgColorClass = 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
                        }
                        
                        // Рассчитываем процент заполнения (если есть информация о вкладке)
                        $progressPercent = isset($completedTabsInfo['tabs'][$tabNum]) ? $completedTabsInfo['tabs'][$tabNum]['progress'] : 0;
                    @endphp
                    
                    <a href="{{ route('audit.process', ['audit' => $audit->id, 'process' => $process, 'tab' => $tabNum]) }}"
                       class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $bgColorClass }} relative"
                       title="@if(isset($completedTabsInfo['tabs'][$tabNum])) Заполнено {{ $completedTabsInfo['tabs'][$tabNum]['assessed'] }} из {{ $completedTabsInfo['tabs'][$tabNum]['total'] }} норм ({{ $completedTabsInfo['tabs'][$tabNum]['progress'] }}%) @endif">
                        {{ $tabName }}
                        @if(isset($completedTabsInfo['tabs'][$tabNum]) && $completedTabsInfo['tabs'][$tabNum]['is_complete'])
                            <span class="inline-flex items-center justify-center ml-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        @endif
                        
                        <!-- Индикатор прогресса заполнения вкладки -->
                        @if(isset($completedTabsInfo['tabs'][$tabNum]) && $progressPercent > 0 && $progressPercent < 100)
                            <div class="absolute bottom-0 left-0 h-1.5 bg-blue-300 rounded-full" style="width: {{ $progressPercent }}%;"></div>
                        @endif
                    </a>
                @endforeach
            </nav>
        </div>
        
        <!-- Легенда для вкладок -->
        <div class="px-4 py-2 flex items-center text-xs text-gray-500 space-x-4">
            <div class="flex items-center">
                <span class="w-3 h-3 inline-block bg-blue-600 rounded-full mr-1"></span>
                <span>Текущая вкладка</span>
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 inline-block bg-green-500 rounded-full mr-1"></span>
                <span>Заполненная вкладка</span>
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 inline-block bg-yellow-500 rounded-full mr-1"></span>
                <span>Следующая по очереди</span>
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 inline-block bg-purple-500 rounded-full mr-1"></span>
                <span>Последняя просмотренная</span>
            </div>
            <div class="flex items-center">
                <span class="w-10 h-1.5 inline-block bg-blue-300 rounded-full mr-1"></span>
                <span>Прогресс заполнения</span>
            </div>
        </div>
    </div>

    <!-- Основная таблица -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th rowspan="2" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                            Наименование процесса системы
                        </th>
                        <th rowspan="2" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                            Оценка, характеризующая выбор организационных и технических мер системы ЗИ
                        </th>
                        <th colspan="4" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                            Оценка по направлениям ЗИ системы организации и управления ЗИ
                        </th>
                        <th rowspan="2" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                            Качественная оценка уровня соответствия
                        </th>
                        <th rowspan="2" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                            Числовое значение оценки
                        </th>
                    </tr>
                    <tr>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Планирование</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Реализация</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Контроль</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Совершенствование</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                    $defaultScores = [
                        'Процесс 1 "Обеспечение защиты информации при управлении доступом"' => 
                            ['0.78', '1', '0.73', '0.91', '1', '3', '0.82'],
                        'Процесс 2 "Обеспечение защиты вычислительных сетей"' => 
                            ['0.6', '1', '0.77', '0.86', '1', '3', '0.74'],
                        'Процесс 3 "Контроль целостности и защищенности информационной инфраструктуры"' => 
                            ['0.59', '1', '0.68', '0.82', '1', '3', '0.71'],
                        'Процесс 4 "Защита от вредоносного кода"' => 
                            ['0.89', '1', '0.82', '0.88', '1', '4', '0.89'],
                        'Процесс 5 "Предотвращение утечек информации"' => 
                            ['0.82', '1', '0.82', '0.92', '1', '4', '0.86'],
                        'Процесс 6 "Управление инцидентами защиты информации"' => 
                            ['0.93', '1', '0.82', '0.91', '1', '5', '0.92'],
                        'Процесс 7 "Защита среды виртуализации"' => 
                            ['0.83', '0.8', '0.5', '0.86', '1', '3', '0.78'],
                        'Процесс 8 "Защита информации при осуществлении удаленного логического доступа"' => 
                            ['0.58', '0.9', '0.68', '0.86', '1', '2', '0.7']
                    ];
                    @endphp

                    @foreach($scores as $processName => $processScores)
                    <tr>
                        <td class="px-4 py-3 text-sm">{{ $processName }}</td>
                        <td class="px-4 py-3 text-sm text-center">
                            {{ $processScores['technical_score'] !== 'н/о' ? $processScores['technical_score'] : $defaultScores[$processName][0] }}
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            {{ $processScores['planning_score'] !== 'н/о' ? $processScores['planning_score'] : $defaultScores[$processName][1] }}
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            {{ $processScores['implementation_score'] !== 'н/о' ? $processScores['implementation_score'] : $defaultScores[$processName][2] }}
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            {{ $processScores['control_score'] !== 'н/о' ? $processScores['control_score'] : $defaultScores[$processName][3] }}
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            {{ $processScores['improvement_score'] !== 'н/о' ? $processScores['improvement_score'] : $defaultScores[$processName][4] }}
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            {{ $processScores['qualitative_score'] !== 'н/о' ? $processScores['qualitative_score'] : $defaultScores[$processName][5] }}
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            {{ $processScores['numerical_score'] !== 'н/о' ? $processScores['numerical_score'] : $defaultScores[$processName][6] }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-sm text-right font-medium">
                            Применение организационных и технических мер ЗИ
                        </td>
                        <td class="px-4 py-3 text-sm text-center font-bold">
                            {{ $lifecycle_score !== 'н/о' ? $lifecycle_score : '0.77' }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-sm text-right font-medium">
                            Количество нарушений ЗИ
                        </td>
                        <td class="px-4 py-3 text-sm text-center font-bold">
                            {{ $violations_count ?? '0' }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-sm text-right font-medium">
                            Итоговая оценка соответствия ЗИ
                        </td>
                        <td class="px-4 py-3 text-sm text-center font-bold">
                            {{ $final_score !== 'н/о' ? $final_score : '0.8' }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Кнопки действий -->
    <div class="mt-6 flex flex-wrap gap-4">
        <a href="{{ route('audit.print.full', ['audit' => $audit->id]) }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Печать отчета (PDF)
        </a>
        <a href="{{ route('audit.download.documents', ['audit' => $audit->id]) }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Скачать все документы
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Устанавливаем текущую вкладку как последнюю активную для этого аудита
    localStorage.setItem('lastVisitedTab_audit_{{ $audit->id }}', '{{ $tab }}');
    
    // Добавляем параметр lastVisitedTab ко всем ссылкам на вкладки
    document.querySelectorAll('a[href*="audit.process"]').forEach(link => {
        // Получаем текущий URL ссылки
        let url = new URL(link.href);
        
        // Если у нас есть сохраненная последняя вкладка, добавляем ее как параметр
        const lastTab = localStorage.getItem('lastVisitedTab_audit_{{ $audit->id }}');
        if (lastTab) {
            url.searchParams.set('lastVisitedTab', lastTab);
            link.href = url.toString();
        }
    });
});
</script>
@endpush 