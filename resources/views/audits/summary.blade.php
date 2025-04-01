@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow w-full">
        <!-- Вкладки -->
        @include('audits.partials.tabs')

        <div class="p-6">
            <h2 class="text-xl font-semibold mb-4">Таблица В.14 - Форма таблицы итоговых оценок по результатам оценки соответствия ЗИ</h2>

            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th rowspan="2" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Наименование процесса системы
                        </th>
                        <th rowspan="2" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Оценка, характеризующая выбор организационных и технических мер системы ЗИ
                        </th>
                        <th colspan="4" class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Оценка по направлениям ЗИ системы организации и управления ЗИ
                        </th>
                        <th rowspan="2" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Качественная оценка уровня соответствия
                        </th>
                        <th rowspan="2" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Числовое значение оценки
                        </th>
                    </tr>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Планирование</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Реализация</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Контроль</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Совершенствование</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($summaryData['processes'] as $process)
                    <tr>
                        <td class="px-6 py-4 whitespace-normal text-sm text-gray-900">
                            {{ $process['name'] }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $process['tech_score'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $process['planning_score'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $process['realization_score'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $process['control_score'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $process['improvement_score'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $process['quality_score'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $process['numeric_score'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-right font-medium">
                            Применение организационных и технических мер И на этапах жизненного цикла АС
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $summaryData['lifecycle_score'] }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center font-bold">
                            Итоговая оценка соответствия ЗИ с учетом выявленных нарушений ЗИ
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-right">
                            Количество нарушений ЗИ, выявленных в результате оценки соответствия ЗИ
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $summaryData['violations_count'] }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-right font-bold">
                            Итоговая оценка соответствия ЗИ
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">
                            {{ $summaryData['final_score'] }}
                        </td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-6 flex justify-between">
                <a href="{{ route('organizations.audits.assessments.update', ['organization' => $organization->id, 'audit' => $audit->id]) }}" 
                   class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Обновить оценку
                </a>
                <a href="{{ route('organizations.audits.update', ['organization' => $organization->id, 'audit' => $audit->id]) }}" 
                   onclick="event.preventDefault(); document.getElementById('complete-audit-form').submit();"
                   class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    Закончить аудит
                </a>
            </div>

            <form id="complete-audit-form" 
                  action="{{ route('organizations.audits.update', ['organization' => $organization->id, 'audit' => $audit->id]) }}" 
                  method="POST" style="display: none;">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="completed">
            </form>
        </div>
    </div>
</div>
@endsection 