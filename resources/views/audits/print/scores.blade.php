<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Оценки процесса</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        table { 
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td { 
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        .subprocess-header { 
            font-weight: bold;
            margin: 20px 0 10px;
        }
        .total-score { 
            font-weight: bold;
        }
        @media print {
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <h2>{{ $processName }}</h2>
    
    @foreach($groupedNorms as $processName => $subprocesses)
        @foreach($subprocesses as $subprocessName => $norms)
            <div class="subprocess-header">{{ $subprocessName }}</div>
            <table>
                <thead>
                    <tr>
                        <th>Условное обозначение и номер меры</th>
                        <th>Содержание мер системы защиты информации</th>
                        <th width="100">Оценка</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($norms as $norm)
                        <tr>
                            <td>{{ $norm->code }}</td>
                            <td>{{ $norm->description }}</td>
                            <td>{{ $norm->assessments->first()?->score ?? 'н/о' }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-score">
                        <td colspan="2">Итоговая оценка за подпроцесс</td>
                        <td>{{ $scores[$subprocessName] ?? 'н/о' }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    @endforeach
</body>
</html> 