<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CIBIT-57580</title>
    <script src="https://cibit.gost-r-57580.ru/js/app.js" defer></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://cibit.gost-r-57580.ru/css/app.css" rel="stylesheet">
</head>
<body>
<div id="app">
    <div class="container-fluid col-xs-7 col-md-7">
        <div class="mt-4 mb-3 d-flex justify-content-end">
            <button onclick="window.print()" class="btn btn-primary" style="margin-right: 20px;">
                Печать
            </button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                Назад
            </a>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            @foreach($groupedNorms as $processName => $subprocesses)
                                <thead class="thead-light">
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            <strong>Процесс {{ $loop->iteration }} "{{ $processName }}"</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Условное обозначение и номер меры</strong></td>
                                        <td><strong>Содержание мер системы защиты информации</strong></td>
                                        <td><strong>Оценка</strong></td>
                                    </tr>
                                </thead>

                                @foreach($subprocesses as $subprocessName => $norms)
                                    <thead class="thead-light">
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <strong>Подпроцесс "{{ $subprocessName }}"</strong>
                                            </td>
                                        </tr>
                                    </thead>

                                    @foreach($norms as $norm)
                                        <tr>
                                            <td>{{ $norm->code }}</td>
                                            <td>{!! nl2br(e($norm->description)) !!}</td>
                                            <td style="width: 200px;">
                                                @if(isset($norm->assessments->first()->score))
                                                    @if($norm->assessments->first()->score === null)
                                                        н/о
                                                    @else
                                                        {{ $norm->assessments->first()->score }}
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="2" class="text-right">Итоговая оценка за подпроцесс</td>
                                        <td> н/о </td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="2" class="text-right">Итоговая оценка за процесс</td>
                                    <td> 0 </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html> 