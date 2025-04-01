<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Полный отчет по аудиту #{{ $audit->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        h1, h2, h3 {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <h1>Полный отчет по аудиту #{{ $audit->id }}</h1>
    <h2>{{ $organization->name }}</h2>
    <p><strong>Дата начала:</strong> {{ $audit->start_date->format('d.m.Y') }}</p>
    <p><strong>Дата окончания:</strong> {{ $audit->end_date ? $audit->end_date->format('d.m.Y') : 'Не завершен' }}</p>
    
    <!-- Итоговая оценка -->
    <h2>Итоговая оценка</h2>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Процесс</th>
                <th colspan="5">Оценка выполнения требований по направлениям</th>
                <th rowspan="2">Итоговая оценка</th>
            </tr>
            <tr>
                <th>Техническая защита</th>
                <th>Планирование</th>
                <th>Реализация</th>
                <th>Контроль</th>
                <th>Совершенствование</th>
            </tr>
        </thead>
        <tbody>
            @foreach($printData['itogo']['scores'] as $processName => $score)
            <tr>
                <td>{{ $processName }}</td>
                <td>{{ $score['technical_score'] ?? 'н/о' }}</td>
                <td>{{ $score['planning_score'] ?? 'н/о' }}</td>
                <td>{{ $score['implementation_score'] ?? 'н/о' }}</td>
                <td>{{ $score['control_score'] ?? 'н/о' }}</td>
                <td>{{ $score['improvement_score'] ?? 'н/о' }}</td>
                <td>
                    @if(isset($score['numerical_score']) && $score['numerical_score'] !== 'н/о')
                        {{ $score['numerical_score'] }} ({{ $score['qualitative_score'] }})
                    @else
                        н/о
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right"><strong>Итоговая оценка по жизненному циклу:</strong></td>
                <td>{{ $printData['itogo']['lifecycle_score'] }}</td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><strong>Количество нарушений:</strong></td>
                <td>{{ $printData['itogo']['violations_count'] }}</td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><strong>Итоговая оценка:</strong></td>
                <td>{{ $printData['itogo']['final_score'] }}</td>
            </tr>
        </tfoot>
    </table>
    
    <div class="page-break"></div>
    
    <!-- Списки норм по вкладкам -->
    <h2>Списки норм</h2>
    @foreach($printData['lists'] as $list)
        <h3>{{ $list['processName'] }} (Вкладка {{ $list['tab'] }})</h3>
        <table>
            <thead>
                <tr>
                    <th>Код</th>
                    <th>Описание</th>
                    <th>Оценка</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $currentSubprocess = null;
                @endphp

                @foreach($list['norms'] as $norm)
                    @if($norm->subprocess_name != $currentSubprocess)
                        <tr>
                            <td colspan="3" class="text-center">
                                <strong>Подпроцесс "{{ $norm->subprocess_name }}"</strong>
                            </td>
                        </tr>
                        @php
                            $currentSubprocess = $norm->subprocess_name;
                            $subprocessScores = [];
                        @endphp
                    @endif

                    <tr>
                        <td>{{ $norm->code }}</td>
                        <td>{{ $norm->description }}</td>
                        <td style="width: 200px;">
                            @if($norm->assessments->isNotEmpty())
                                {{ $norm->assessments->first()->score }}
                                @php
                                    $subprocessScores[] = $norm->assessments->first()->score;
                                @endphp
                            @endif
                        </td>
                    </tr>
                @endforeach
                
                <tr>
                    <td colspan="2" class="text-right">Итоговая оценка за подпроцесс</td>
                    <td> {{ !empty($subprocessScores) ? min($subprocessScores) : 'н/о' }} </td>
                </tr>
            </tbody>
        </table>
        
        <div class="page-break"></div>
    @endforeach
    
    <!-- Свидетельства -->
    <h2>Свидетельства</h2>
    @foreach($printData['evidences'] as $evidence)
        @foreach($evidence['processesWithEvidences'] as $processName => $norms)
            <h3>{{ $processName }} (Вкладка {{ $evidence['tab'] }})</h3>
            <table>
                <thead>
                    <tr>
                        <th>Код</th>
                        <th>Источники свидетельств</th>
                        <th>ФИО и должность</th>
                        <th>Дата</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $evidenceCount = 0; 
                    @endphp
                    
                    @foreach($norms as $norm)
                        @if($norm->evidences && $norm->evidences->count() > 0)
                            @foreach($norm->evidences as $evidenceItem)
                                @php $evidenceCount++; @endphp
                                <tr>
                                    <td>{{ $norm->code }}</td>
                                    <td>
                                        {{ $evidenceItem->description }}
                                        @if($evidenceItem->file_path)
                                            <br><br>
                                            <b>Источники:</b><br>
                                            {{ $evidenceItem->file_path }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $evidenceItem->user ? $evidenceItem->user->name : 'Не указан' }}
                                    </td>
                                    <td>
                                        {{ $evidenceItem->created_at ? $evidenceItem->created_at->format('d.m.Y') : '' }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                    
                    <tr>
                        <td colspan="4" class="text-right">
                            <strong>Итого: {{ $evidenceCount }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <div class="page-break"></div>
        @endforeach
    @endforeach
</body>
</html> 