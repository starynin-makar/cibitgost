// @charset "UTF-8";

export function calculateRatings() {
    const processTable = document.querySelector('table');
    if (!processTable) return;
    
    // Добавляем обработчики на все кнопки оценок
    const buttons = document.querySelectorAll('button[data-value]');
    buttons.forEach(button => {
        button.addEventListener('click', handleRatingClick);
    });

    calculateAverages();
}

function handleRatingClick(event) {
    const button = event.target;
    const value = button.dataset.value;
    const normId = button.dataset.normId;
    
    // Находим все кнопки в группе
    const buttonGroup = button.closest('.btn-group');
    const buttons = buttonGroup.querySelectorAll('button');
    
    // Сбрасываем стили у всех кнопок в группе
    buttons.forEach(btn => {
        btn.classList.remove('bg-red-500', 'bg-green-500', 'bg-gray-800', 'text-white');
        btn.classList.add('bg-white', 'text-gray-700');
    });
    
    // Применяем стиль к нажатой кнопке
    button.classList.remove('bg-white', 'text-gray-700');
    
    // Устанавливаем цвет в зависимости от значения
    switch(value) {
        case '-1': // н/о
            button.classList.add('bg-gray-800', 'text-white');
            break;
        case '0':
            button.classList.add('bg-red-500', 'text-white');
            break;
        case '1':
            button.classList.add('bg-green-500', 'text-white');
            break;
    }
    
    calculateAverages();
}

function calculateAverages() {
    const table = document.querySelector('table');
    if (!table) return;

    const rows = Array.from(table.querySelectorAll('tr'));
    let currentSubprocess = null;
    let subprocessGrades = [];
    let processGrades = [];

    rows.forEach(row => {
        // Проверяем, является ли строка заголовком подпроцесса
        const subprocessHeader = row.querySelector('td[colspan="6"]');
        if (subprocessHeader && subprocessHeader.textContent.includes('Подпроцесс')) {
            if (currentSubprocess && subprocessGrades.length > 0) {
                const average = calculateSubprocessAverage(subprocessGrades);
                if (average !== 'н/о') {
                    processGrades.push(parseFloat(average));
                }
                updateSubprocessAverage(currentSubprocess, average);
            }
            currentSubprocess = row;
            subprocessGrades = [];
            return;
        }

        // Собираем оценки текущего подпроцесса
        const activeButton = row.querySelector('button.bg-green-500, button.bg-red-500, button.bg-gray-800');
        if (activeButton) {
            const value = activeButton.dataset.value;
            if (value === '-1') {
                subprocessGrades.push('н/о');
            } else if (!isNaN(parseInt(value))) {
                subprocessGrades.push(parseInt(value));
            }
        }
    });

    // Обрабатываем последний подпроцесс
    if (currentSubprocess && subprocessGrades.length > 0) {
        const average = calculateSubprocessAverage(subprocessGrades);
        if (average !== 'н/о') {
            processGrades.push(parseFloat(average));
        }
        updateSubprocessAverage(currentSubprocess, average);
    }

    // Рассчитываем общее среднее процесса
    const processAverage = calculateProcessAverage(processGrades);
    updateProcessAverage(table, processAverage);
}

function calculateSubprocessAverage(grades) {
    if (grades.includes('н/о') || grades.length === 0) {
        return 'н/о';
    }
    const sum = grades.reduce((acc, grade) => acc + grade, 0);
    return (sum / grades.length).toFixed(2);
}

function calculateProcessAverage(grades) {
    if (grades.length === 0) return '0.00';
    const sum = grades.reduce((acc, grade) => acc + grade, 0);
    return (sum / grades.length).toFixed(2);
}

function updateSubprocessAverage(subprocessRow, average) {
    let currentRow = subprocessRow;
    while (currentRow.nextElementSibling) {
        currentRow = currentRow.nextElementSibling;
        if (currentRow.querySelector('td').textContent.includes('Итоговая оценка за подпроцесс')) {
            const averageCell = currentRow.querySelector('td:nth-child(4)');
            if (averageCell) {
                averageCell.textContent = average;
                break;
            }
        }
    }
}

function updateProcessAverage(table, average) {
    const rows = Array.from(table.querySelectorAll('tr'));
    for (let i = rows.length - 1; i >= 0; i--) {
        const firstCell = rows[i].querySelector('td');
        if (firstCell && firstCell.textContent.includes('Итоговая оценка за процесс')) {
            const averageCell = rows[i].querySelector('td:nth-child(4)');
            if (averageCell) {
                averageCell.textContent = average;
                break;
            }
        }
    }
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', calculateRatings);