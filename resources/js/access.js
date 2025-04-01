// Инициализация Select2 для множественного выбора
$(document).ready(function() {
    $('#organizations').select2({
        placeholder: 'Выберите организации',
        allowClear: true
    });
}); 