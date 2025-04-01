<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Лист свидетельств</title>
    <style>
        @page { margin: 2cm; }
        body { 
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }
        .print-container {
            max-width: 21cm;
            margin: 0 auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 4px 8px;
            text-align: left;
        }
        .process-header {
            font-weight: bold;
            font-size: 14px;
            margin: 20px 0 10px;
        }
        .subprocess-header {
            font-weight: bold;
            padding: 10px 0;
        }
        .signature-cell {
            height: 40px;
        }
    </style>
</head>
<body>
    <div class="print-container">
        @foreach($groupedNorms as $processName => $norms)
            <div class="process-header">{{ $processName }}</div>
            
            @php
                $subprocessGroups = $norms->groupBy('subprocess_name');
            @endphp

            @foreach($subprocessGroups as $subprocessName => $subprocessNorms)
                <div class="subprocess-header">{{ $subprocessName }}</div>
                <table>
                    <thead>
                        <tr>
                            <th>Код</th>
                            <th>Свидетельства</th>
                            <th>ФИО и должность</th>
                            <th style="width: 150px;">Подпись</th>
                            <th style="width: 100px;">Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subprocessNorms as $norm)
                            <tr>
                                <td>{{ $norm->code }}</td>
                                <td>{{ $norm->evidence }}</td>
                                <td>{{ $norm->employee }}</td>
                                <td class="signature-cell"></td>
                                <td>{{ now()->format('d.m.Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        @endforeach
    </div>
</body>
</html> 