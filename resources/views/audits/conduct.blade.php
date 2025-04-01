@extends('layouts.app')

@section('content')
<div class="container-custom">
    <!-- Хлебные крошки -->
    <div class="text-sm text-gray-600 mb-8">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Главная</a>
        <span class="mx-2">/</span>
        <a href="{{ route('organizations.index') }}" class="hover:text-blue-600">Организации</a>
        <span class="mx-2">/</span>
        <span>Аудит</span>
    </div>

    <!-- Заголовок и вкладки -->
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
        <div class="px-4 py-2 flex justify-end space-x-2">
            <a href="{{ route('audit.print', ['audit' => $audit->id, 'process' => $process, 'tab' => $tab]) }}" 
               target="_blank"
               class="inline-flex items-center px-3 py-1 border border-gray-300 rounded text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                P
            </a>
            <a href="{{ route('audit.print.evidence', ['audit' => $audit->id, 'process' => $process, 'tab' => $tab]) }}"
               target="_blank" 
               class="inline-flex items-center px-3 py-1 border border-gray-300 rounded text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                L
            </a>
            <a href="{{ route('audit.download.tab.documents', ['audit' => $audit->id, 'process' => $process, 'tab' => $tab]) }}?debug=true"
               class="inline-flex items-center px-3 py-1 border border-gray-300 rounded text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                </svg>
                Скачать документы
            </a>
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

    <!-- Таблица -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Код</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Описание</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Оценка</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Тип</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($groupedNorms as $processName => $subprocesses)
                        <!-- Заголовок процесса -->
                        <tr class="bg-gray-50">
                            <td colspan="6" class="px-6 py-4">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-gray-900">{{ $processName }}</span>
                                    <div class="text-sm text-gray-500">
                                        <span class="process-score" data-process="{{ $processName }}">
                                            Оценка процесса: <span class="score-value">0</span>
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Группируем нормы по подпроцессам -->
                        @php
                            $subprocessGroups = $norms->where('process_name', $processName)->groupBy('subprocess_name');
                        @endphp

                        @foreach($subprocessGroups as $subprocessName => $subprocessNorms)
                            <!-- Заголовок подпроцесса -->
                            <tr class="bg-gray-100">
                                <td colspan="6" class="px-6 py-2">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium text-gray-800">{{ $subprocessName }}</span>
                                        <div class="text-sm text-gray-500">
                                            <span class="subprocess-score" data-subprocess="{{ $subprocessName }}">
                                                Оценка подпроцесса: <span class="score-value">0</span>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- Нормы подпроцесса -->
                            @foreach($subprocessNorms as $norm)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $norm->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $norm->code }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $norm->description }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-2">
                                            <button onclick="saveAssessment({{ $norm->id }}, 0)"
                                                    data-norm-id="{{ $norm->id }}"
                                                    data-process="{{ $norm->process_name }}"
                                                    class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded
                                                        {{ isset($norm->assessments->first()->score) && $norm->assessments->first()->score === 0 
                                                            ? 'bg-red-100 text-red-800 hover:bg-red-200'
                                                            : 'bg-gray-100 text-gray-800 hover:bg-gray-200'
                                                        }}">
                                                0
                                            </button>
                                            @if(in_array($tab, [9, 10, 11, 12, 13]))
                                            <button onclick="saveAssessment({{ $norm->id }}, 0.5)"
                                                    data-norm-id="{{ $norm->id }}"
                                                    data-process="{{ $norm->process_name }}"
                                                    class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded
                                                        {{ isset($norm->assessments->first()->score) && $norm->assessments->first()->score == 0.5
                                                            ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200'
                                                            : 'bg-gray-100 text-gray-800 hover:bg-gray-200'
                                                        }}">
                                                0.5
                                            </button>
                                            @endif
                                            <button onclick="saveAssessment({{ $norm->id }}, 1)"
                                                    data-norm-id="{{ $norm->id }}"
                                                    data-process="{{ $norm->process_name }}"
                                                    class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded
                                                        {{ isset($norm->assessments->first()->score) && $norm->assessments->first()->score === 1
                                                            ? 'bg-green-100 text-green-800 hover:bg-green-200'
                                                            : 'bg-gray-100 text-gray-800 hover:bg-gray-200'
                                                        }}">
                                                1
                                            </button>
                                            @if($tab != 14)
                                            <button onclick="saveAssessment({{ $norm->id }}, null)"
                                                    data-norm-id="{{ $norm->id }}"
                                                    data-process="{{ $norm->process_name }}"
                                                    class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded
                                                        {{ isset($norm->assessments->first()->score) && ($norm->assessments->first()->score === 'н/о' || $norm->assessments->first()->score === -1)
                                                            ? 'bg-gray-800 text-white hover:bg-gray-700'
                                                            : 'bg-gray-100 text-gray-800 hover:bg-gray-200'
                                                        }}">
                                                н/о
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $norm->implementation_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('audit.evidence', ['organization' => $organization->id, 'audit' => $audit->id, 'norm' => $norm->id]) }}" 
                                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Добавить свидетельство
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Модальное окно для загрузки свидетельств -->
<div id="evidenceModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Добавить свидетельство</h2>
            
            <form id="evidenceForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="normId" name="norm_id">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="file">
                        Файл
                    </label>
                    <input type="file" 
                           name="file" 
                           id="file"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                        Описание
                    </label>
                    <input type="text"
                           name="description"
                           id="description"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeEvidenceModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Отмена
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                        Загрузить
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentAuditId = {{ $audit->id }};
let currentTab = {{ $tab }};
let allowHalfScore = [9, 10, 11, 12, 13].includes(currentTab); // B10, B11, B12, B13, ЖЦ AC
let isNziTab = currentTab === 14; // НЗИ (Z)

// Функции для расчета средних значений
function calculateAverages() {
    // Расчет для каждого подпроцесса
    document.querySelectorAll('.subprocess-score').forEach(subprocessElement => {
        const subprocessRow = subprocessElement.closest('tr');
        const scores = [];
        
        // Собираем оценки норм подпроцесса
        let currentRow = subprocessRow.nextElementSibling;
        while (currentRow && !currentRow.classList.contains('bg-gray-100') && !currentRow.classList.contains('bg-gray-50')) {
            // Ищем активную кнопку с соответствующим фоном в зависимости от вкладки
            // Для вкладок B10-B13, ЖЦ АС включаем желтые кнопки (0.5)
            // Для вкладки НЗИ (Z) проверяем только красный и зеленый фон (0 и 1)
            // Для остальных вкладок используем красный, зеленый и серый фон (0, 1, н/о)
            let activeButtonSelector;
            
            if (isNziTab) {
                // Для НЗИ (Z) только 0 и 1 (без н/о)
                activeButtonSelector = 'button.bg-red-100, button.bg-green-100';
            } else if (allowHalfScore) {
                // Для B10-B13, ЖЦ АС включаем 0.5
                activeButtonSelector = 'button.bg-red-100, button.bg-yellow-100, button.bg-green-100, button.bg-gray-800';
            } else {
                // Для остальных вкладок 0, 1, н/о
                activeButtonSelector = 'button.bg-red-100, button.bg-green-100, button.bg-gray-800';
            }
            
            const activeButton = currentRow.querySelector(activeButtonSelector);
            if (activeButton) {
                const btnText = activeButton.textContent.trim();
                if (btnText === 'н/о') {
                    // Пропускаем оценку "н/о" при расчете среднего
                    // score не добавляем в массив
                } else {
                    const score = parseFloat(btnText);
                    if (!isNaN(score)) {
                        scores.push(score);
                    }
                }
            }
            currentRow = currentRow.nextElementSibling;
        }
        
        // Вычисляем среднее для подпроцесса
        const average = scores.length > 0 ? scores.reduce((a, b) => a + b, 0) / scores.length : 0;
        const scoreValue = subprocessElement.querySelector('.score-value');
        if (scoreValue) {
            scoreValue.textContent = average.toFixed(2);
        }
    });

    // Расчет для каждого процесса
    document.querySelectorAll('.process-score').forEach(processElement => {
        const processRow = processElement.closest('tr');
        const scores = [];
        
        // Собираем оценки подпроцессов
        let currentRow = processRow.nextElementSibling;
        while (currentRow && !currentRow.classList.contains('bg-gray-50')) {
            if (currentRow.classList.contains('bg-gray-100')) {
                const scoreElement = currentRow.querySelector('.score-value');
                if (scoreElement) {
                    const score = parseFloat(scoreElement.textContent);
                    if (!isNaN(score)) {
                        scores.push(score);
                    }
                }
            }
            currentRow = currentRow.nextElementSibling;
        }
        
        // Вычисляем среднее для процесса
        const average = scores.length > 0 ? scores.reduce((a, b) => a + b, 0) / scores.length : 0;
        const scoreValue = processElement.querySelector('.score-value');
        if (scoreValue) {
            scoreValue.textContent = average.toFixed(2);
        }
    });
}

async function saveAssessment(normId, score) {
    try {
        const button = event.target;
        const processName = button.getAttribute('data-process');
        
        // Если значение score равно null, и кнопка содержит "н/о", отправляем -1
        // В противном случае отправляем score как есть
        let scoreToSend = score;
        if (score === null && button.textContent.trim() === 'н/о') {
            scoreToSend = -1;
        }
        
        // Проверяем, что оценка 0.5 доступна для данной вкладки
        if (scoreToSend === 0.5 && !allowHalfScore) {
            console.warn('Оценка 0.5 недоступна для этой вкладки');
            showNotification('Оценка 0.5 недоступна для этой вкладки', 'error');
            return;
        }
        
        // Проверяем, что оценка "н/о" недоступна для вкладки НЗИ (Z)
        if (scoreToSend === -1 && isNziTab) {
            console.warn('Оценка "н/о" недоступна для вкладки НЗИ (Z)');
            showNotification('Для вкладки НЗИ (Z) доступны только оценки 0 и 1', 'error');
            return;
        }
        
        console.log('Saving assessment:', {
            normId,
            score: scoreToSend,
            processName,
            auditId: currentAuditId
        });

        const response = await fetch('/organizations/{{ $organization->id }}/audits/' + currentAuditId + '/assessments', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                norm_id: normId,
                score: scoreToSend,
                process_name: processName
            })
        });

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP error! status: ${response.status}, message: ${errorText}`);
        }

        const data = await response.json();
        console.log('Response:', data);

        if (data.success) {
            // Обновляем стили всех кнопок для этой нормы
            const buttons = document.querySelectorAll(`button[data-norm-id="${normId}"]`);
            buttons.forEach(btn => {
                // Сначала сбрасываем стили
                btn.className = 'inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded bg-gray-100 text-gray-800 hover:bg-gray-200';
                
                // Получаем значение кнопки: н/о = -1, 0 = 0, 0.5 = 0.5, 1 = 1
                const btnText = btn.textContent.trim();
                const btnValue = btnText === 'н/о' ? -1 : parseFloat(btnText);
                
                if ((btnValue === 0 && scoreToSend === 0) ||
                    (btnValue === 0.5 && scoreToSend === 0.5) ||
                    (btnValue === 1 && scoreToSend === 1) ||
                    (btnValue === -1 && scoreToSend === -1)) {
                    
                    if (scoreToSend === 0) {
                        btn.className = 'inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded bg-red-100 text-red-800 hover:bg-red-200';
                    } else if (scoreToSend === 0.5) {
                        btn.className = 'inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded bg-yellow-100 text-yellow-800 hover:bg-yellow-200';
                    } else if (scoreToSend === 1) {
                        btn.className = 'inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded bg-green-100 text-green-800 hover:bg-green-200';
                    } else if (scoreToSend === -1) {
                        btn.className = 'inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded bg-gray-800 text-white hover:bg-gray-700';
                    }
                }
            });

            // Пересчитываем средние значения
            calculateAverages();
            showNotification('Оценка сохранена', 'success');
        } else {
            throw new Error(data.message || 'Ошибка сохранения');
        }
    } catch (error) {
        console.error('Error saving assessment:', error);
        showNotification('Ошибка при сохранении оценки: ' + error.message, 'error');
    }
}

function showNotification(message, type = 'success') {
    // Удаляем предыдущие уведомления
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());

    // Создаем новое уведомление
    const notification = document.createElement('div');
    notification.className = `notification fixed top-4 right-4 px-6 py-3 rounded-lg text-white ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } transition-opacity duration-500 z-50`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 500);
    }, 3000);
}

// Вызываем расчет при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    calculateAverages();
});

document.addEventListener('DOMContentLoaded', function() {
    const auditId = '{{ $audit->id }}';
    
    // Добавляем обработчики для оценок в таблице норм
    document.querySelectorAll('.score-select').forEach(select => {
        select.addEventListener('change', async function() {
            const normId = this.getAttribute('data-norm-id');
            const processName = this.getAttribute('data-process');
            const score = this.value;
            
            try {
                const response = await fetch('/api/process-scores/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        audit_id: auditId,
                        process_name: processName,
                        norm_id: normId,
                        score: score
                    })
                });

                if (!response.ok) throw new Error('Network response was not ok');
                
                const data = await response.json();
                if (data.success) {
                    // Обновляем отображение оценки
                    updateScoreDisplay(this, score);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });

    function updateScoreDisplay(select, score) {
        // Обновляем класс и цвет ячейки в зависимости от оценки
        const cell = select.closest('td');
        cell.className = getScoreClass(score);
    }

    function getScoreClass(score) {
        switch(score) {
            case '3': return 'bg-green-100 text-green-800';
            case '2': return 'bg-yellow-100 text-yellow-800';
            case '1':
            case '0': return 'bg-red-100 text-red-800';
            default: return '';
        }
    }
});

function openEvidenceModal(normId) {
    document.getElementById('normId').value = normId;
    document.getElementById('evidenceModal').classList.remove('hidden');
    
    // Устанавливаем правильный URL для формы
    const form = document.getElementById('evidenceForm');
    form.action = `/organizations/{{ $organization->id }}/audits/{{ $audit->id }}/conduct/evidence`;
}

function closeEvidenceModal() {
    document.getElementById('evidenceModal').classList.add('hidden');
    document.getElementById('evidenceForm').reset();
}

// Обработка отправки формы
document.getElementById('evidenceForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            closeEvidenceModal();
            showNotification('Свидетельство успешно добавлено', 'success');
        } else {
            showNotification(data.message || 'Ошибка при загрузке', 'error');
        }
    } catch (error) {
        showNotification('Ошибка при загрузке файла', 'error');
    }
});

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
    
    // Исправляем оценки в таблице
    updateScores();
});

// Функция для обновления оценок в таблице процессов и подпроцессов
function updateScores() {
    const processNames = new Set();
    document.querySelectorAll('.process-score').forEach(el => {
        processNames.add(el.getAttribute('data-process'));
    });

    processNames.forEach(processName => {
        // Получаем все оценки для этого процесса
        const normRows = document.querySelectorAll(`[data-process="${processName}"]`);
        
        // Подсчитываем общее количество оценок и сумму их значений
        let totalAssessed = 0;
        let totalScore = 0;
        
        normRows.forEach(row => {
            const buttons = row.closest('tr').querySelectorAll('button');
            buttons.forEach(btn => {
                if (btn.classList.contains('bg-green-100') || btn.classList.contains('bg-red-100') || btn.classList.contains('bg-yellow-100') || btn.classList.contains('bg-gray-800')) {
                    totalAssessed++;
                    const score = btn.textContent.trim();
                    if (score === '1') {
                        totalScore += 1;
                    } else if (score === '0.5') {
                        totalScore += 0.5;
                    } else if (score === '0') {
                        totalScore += 0;
                    }
                    // Оценка "н/о" не учитывается в расчете средней оценки
                }
            });
        });
        
        // Рассчитываем среднюю оценку для процесса
        const avgScore = totalAssessed > 0 ? (totalScore / totalAssessed).toFixed(2) : '0';
        
        // Обновляем отображение оценки процесса
        document.querySelectorAll(`.process-score[data-process="${processName}"] .score-value`)
            .forEach(el => el.textContent = avgScore);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Устанавливаем текущую вкладку как последнюю активную для этого аудита
    localStorage.setItem('lastVisitedTab_audit_{{ $audit->id }}', '{{ $tab }}');
    
    // Исправляем оценки в таблице
    updateScores();
});
</script>
@endpush 