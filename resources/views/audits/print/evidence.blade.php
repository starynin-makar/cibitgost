<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CIBIT-57580</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
        }
        .table-container {
            margin-top: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .btn-container {
            margin-bottom: 20px;
            text-align: right;
        }
        .btn {
            display: inline-block;
            font-weight: 400;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            text-decoration: none;
            margin-left: 10px;
        }
        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }
        @media print {
            .btn-container {
                display: none;
            }
            body {
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body>
<div id="app">
    <div class="container-fluid">
        <div class="btn-container">
            <button onclick="window.print()" class="btn btn-primary">
                Печать
            </button>
            <a href="{{ route('audit.process', ['audit' => $audit->id, 'process' => $process, 'tab' => $tab]) }}" class="btn btn-secondary">
                Назад
            </a>
        </div>

        <div class="table-container">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th colspan="5" class="text-center">
                            <strong>Направление 4 &quot;Совершенствование процесса системы защиты информации&quot;</strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($processesWithEvidences as $processName => $norms)
                        <tr>
                            <th colspan="5" class="text-center">
                                <strong>{{ $processName }}</strong>
                            </th>
                        </tr>
                        <tr>
                            <th>Условное обозначение и номер меры</th>
                            <th>Источники свидетельств оценки соответствия ЗИ (документы, результаты опроса и наблюдений)</th>
                            <th>ФИО и должность сотрудника (сотрудников) проверяемой организации, предоставившего (предоставивших) свидетельства оценки соответствия ЗИ</th>
                            <th>Подписи члена(членов) проверяющей группы и сотрудника (сотрудников) проверяемой организации</th>
                            <th>Дата</th>
                        </tr>

                        @php 
                            $evidenceCount = 0; 
                        @endphp
                        
                        @foreach($norms as $norm)
                            @if($norm->evidences && $norm->evidences->count() > 0)
                                @foreach($norm->evidences as $evidence)
                                    @php $evidenceCount++; @endphp
                                    <tr>
                                        <td>{{ $norm->code }}</td>
                                        <td>
                                            {{ $evidence->description }}
                                            @if($evidence->file_path)
                                                <br><br>
                                                <b>Источники свидетельств:</b><br>
                                                {{ $evidence->file_path }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $evidence->user ? $evidence->user->name : 'Не указан' }}
                                        </td>
                                        <td></td>
                                        <td>
                                            {{ $evidence->created_at ? $evidence->created_at->format('d.m.Y') : '' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                        
                        <tr>
                            <td colspan="5" class="text-right">
                                <strong>Итого: {{ $evidenceCount }}</strong>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html> 