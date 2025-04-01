<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Печать</title>
    
    <!-- Подключаем стили Tailwind из Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        @media print {
            body {
                font-size: 12pt;
            }
            
            .no-print {
                display: none;
            }
            
            @page {
                margin: 2cm;
                size: A4;
            }
            
            table {
                border-collapse: collapse;
            }
            
            td, th {
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body class="bg-white">
    <div class="no-print mb-4 p-4 bg-gray-100">
        <button onclick="window.print()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Печать
        </button>
        <button onclick="window.close()" class="px-4 py-2 bg-gray-500 text-white rounded ml-2 hover:bg-gray-600">
            Закрыть
        </button>
    </div>

    @yield('content')
</body>
</html> 