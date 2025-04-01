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
</head>
<body>
<div id="app">
    <div class="container-fluid col-xs-7 col-md-7">
        <div class="mt-4 mb-3 d-flex justify-content-end">
            <button onclick="window.print()" class="btn btn-primary" style="margin-right: 20px;">
                Печать
            </button>
            <a href="{{ route('audit.process', ['audit' => $audit->id, 'process' => $process, 'tab' => $tab]) }}" class="btn btn-secondary">
                Назад
            </a>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <thead class="thead-light">
                                <tr>
                                    <td colspan="3" class="text-center">
                                        <strong>{{ $processName }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Условное обозначение и номер меры</strong></td>
                                    <td><strong>Содержание мер системы защиты информации</strong></td>
                                    <td><strong>Оценка</strong></td>
                                </tr>
                            </thead>

                            @php
                                $currentSubprocess = null;
                            @endphp

                            @foreach($norms as $norm)
                                @if($norm->subprocess_name != $currentSubprocess)
                                    <thead class="thead-light">
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <strong>Подпроцесс "{{ $norm->subprocess_name }}"</strong>
                                            </td>
                                        </tr>
                                    </thead>
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
                            
                            <tr>
                                <td colspan="2" class="text-right">Итоговая оценка за процесс</td>
                                <td> {{ $norms->first()?->assessments->first()?->score ?? 'н/о' }} </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html> 