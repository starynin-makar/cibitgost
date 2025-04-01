<!-- Удалить или закомментировать следующий код -->
<a href="{{ route('audit.print.list', ['audit' => $audit->id, 'process' => $process, 'tab' => $tab]) }}" class="btn btn-primary">
    <i class="fas fa-print"></i> Печать
</a> 