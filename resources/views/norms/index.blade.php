@extends('layouts.app')

@section('content')
<div class="container-custom">
    <div class="breadcrumbs">
        <a href="{{ route('dashboard') }}">Главная</a> > <span>Нормы</span>
    </div>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Список норм для проведения аудита</h1>
        <button id="copyRowBtn" style="display: none;" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
                onclick="copySelectedRow()">
            Копировать строку
        </button>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table id="dataTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)"
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Номер</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Описание</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Комментарий</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" 
                               class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                               onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.1</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Осуществление логического доступа пользователями и эксплуатационным персоналом под уникальными и персонифицированными учетными записями
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-1"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(1)"
                                class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.2</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Контроль соответствия фактического состава разблокированных учетных записей фактическому составу легальных субъектов логического доступа
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-2"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(2)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">3</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.3</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Контроль отсутствия незаблокированных учетных записей: - уволенных работников; - работников, отсутствующих на рабочем месте более 90 календарных дней; - работников внешних (подрядных) организаций, прекративших свою деятельность в организации
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-3"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(3)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">4</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.4</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Контроль отсутствия незаблокированных учетных записей неопределенного целевого назначения
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-4"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(4)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">5</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.5</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Документарное определение правил предоставления (отзыва) и блокирования логического доступа
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-5"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(5)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">6</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.6</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Назначение для всех ресурсов доступа распорядителя логического доступа (владельца ресурса доступа)
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-6"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(6)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">7</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.7</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Предоставление прав логического доступа по решению распорядителя логического доступа (владельца ресурса доступа)
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-7"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(7)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">8</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.8</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Хранение эталонной информации о предоставленных правах логического доступа и обеспечение целостности указанной информации
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-8"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(8)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">9</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.9</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Контроль соответствия фактических прав логического доступа эталонной информации о предоставленных правах логического доступа
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-9"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(9)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.10</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Исключение возможного бесконтрольного самостоятельного расширения пользователями предоставленных им прав логического доступа
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-10"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(10)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">11</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.11</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Исключение возможного бесконтрольного изменения пользователями параметров настроек средств и систем защиты информации, параметров настроек АС, связанных с защитой информации
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-11"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(11)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">12</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.12</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Контроль необходимости отзыва прав субъектов логического доступа при изменении их должностных обязанностей
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-12"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(12)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">13</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.13</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Контроль прекращения предоставления логического доступа и блокирование учетных записей при истечении периода (срока) предоставления логического доступа
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-13"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(13)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">14</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.14</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Установление фактов неиспользования субъектами логического доступа предоставленных им прав на осуществление логического доступа на протяжении периода времени, превышающего 90 дней
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-14"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(14)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.15</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Установление фактов неиспользования субъектами логического доступа предоставленных им прав на осуществление логического доступа на протяжении периода времени, превышающего 45 дней
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-15"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(15)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">16</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.16</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Контроль отсутствия незаблокированных учетных записей неопределенного целевого назначения
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-16"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(16)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">17</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.17</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Контроль отсутствия незаблокированных учетных записей: - уволенных работников; - работников, отсутствующих на рабочем месте более 90 календарных дней; - работников внешних (подрядных) организаций, прекративших свою деятельность в организации
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-17"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(17)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">18</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.18</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Контроль отсутствия незаблокированных учетных записей неопределенного целевого назначения
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-18"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(18)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">19</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.19</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Документарное определение правил предоставления (отзыва) и блокирования логического доступа
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-19"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(19)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">20</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.20</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Назначение для всех ресурсов доступа распорядителя логического доступа (владельца ресурса доступа)
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-20"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(20)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">21</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.21</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Предоставление прав логического доступа по решению распорядителя логического доступа (владельца ресурса доступа)
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-21"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(21)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">22</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.22</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Хранение эталонной информации о предоставленных правах логического доступа и обеспечение целостности указанной информации
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-22"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(22)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">23</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.23</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Контроль соответствия фактических прав логического доступа эталонной информации о предоставленных правах логического доступа
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-23"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(23)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">24</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.24</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Исключение возможного бесконтрольного самостоятельного расширения пользователями предоставленных им прав логического доступа
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-24"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(24)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">25</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.25</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Исключение возможного бесконтрольного изменения пользователями параметров настроек средств и систем защиты информации, параметров настроек АС, связанных с защитой информации
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-25"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(25)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">26</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.26</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Контроль необходимости отзыва прав субъектов логического доступа при изменении их должностных обязанностей
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-26"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(26)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50" draggable="true">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="row-select rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onclick="updateCopyButtonVisibility()">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">27</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">УЗП.27</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Контроль прекращения предоставления логического доступа и блокирование учетных записей при истечении периода (срока) предоставления логического доступа
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Активен
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 comment" id="comment-27"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openPopup(27)" class="text-blue-600 hover:text-blue-900">
                            Добавить комментарий
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Модальное окно для комментария -->
<div id="popup" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Добавить комментарий</h2>
            <textarea id="commentText" rows="4" 
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            <div class="mt-4 flex justify-end space-x-3">
                <button type="button" id="cancelButton"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Отмена
                </button>
                <button type="button" id="addCommentButton"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                    Добавить
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Перемещаем весь JavaScript в одно место -->
<script>
// Глобальная переменная для хранения ID текущей строки
let currentRowId = null;

// Функции для работы с комментариями
function openPopup(rowId) {
    console.log('Opening popup for row:', rowId);
    currentRowId = rowId;
    document.getElementById('popup').classList.remove('hidden');
}

function closePopup() {
    document.getElementById('popup').classList.add('hidden');
    document.getElementById('commentText').value = '';
}

function addComment() {
    const commentText = document.getElementById('commentText').value;
    if (commentText) {
        document.getElementById('comment-' + currentRowId).innerText = commentText;
        closePopup();
    } else {
        alert('Пожалуйста, введите комментарий.');
    }
}

// Функции для работы с таблицей
function toggleSelectAll(selectAllCheckbox) {
    const checkboxes = document.querySelectorAll('.row-select');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    updateCopyButtonVisibility();
}

function updateCopyButtonVisibility() {
    const checkboxes = document.querySelectorAll('.row-select');
    let someChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
    document.getElementById('copyRowBtn').style.display = someChecked ? 'block' : 'none';
}

function copySelectedRow() {
    const rows = document.querySelectorAll('tbody tr');
    for (const row of rows) {
        const checkbox = row.querySelector('.row-select');
        if (checkbox.checked) {
            const clonedRow = row.cloneNode(true);
            clonedRow.querySelector('.row-select').checked = false;
            clonedRow.querySelectorAll('.comment').forEach(comment => {
                comment.id = 'comment-' + (parseInt(comment.id.split('-')[1]) + 1);
            });
            document.querySelector('tbody').insertBefore(clonedRow, row.nextElementSibling);
            updateCopyButtonVisibility();
            break;
        }
    }
}

// Инициализация обработчиков событий после загрузки DOM
document.addEventListener('DOMContentLoaded', function() {
    const popup = document.getElementById('popup');
    const cancelButton = document.getElementById('cancelButton');
    const addCommentButton = document.getElementById('addCommentButton');

    // Обработчики для кнопок в модальном окне
    if (cancelButton) {
        cancelButton.addEventListener('click', closePopup);
    }
    
    if (addCommentButton) {
        addCommentButton.addEventListener('click', addComment);
    }

    // Закрытие по клику вне модального окна
    if (popup) {
        popup.addEventListener('click', function(e) {
            if (e.target === popup) {
                closePopup();
            }
        });
    }
});
</script>
@endsection 