import './bootstrap';
import { calculateRatings } from './ratings';

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    calculateRatings();
});

// Экспортируем функцию для возможного использования в других местах
window.calculateRatings = calculateRatings;
