document.addEventListener('DOMContentLoaded', function() {
    // Находим все ячейки с оценками (замените на ваш селектор)
    const ratingCells = document.querySelectorAll('td[data-rating]'); // или другой селектор, который соответствует вашей разметке
    
    // Добавляем класс rating-cell к существующим ячейкам
    ratingCells.forEach(cell => {
        cell.classList.add('rating-cell');
    });

    // Остальной код без изменений...
}); 