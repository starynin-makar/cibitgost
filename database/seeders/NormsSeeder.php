<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NormsSeeder extends Seeder
{
    public function run()
    {
        $process = 'Процесс 1 "Обеспечение защиты информации при управлении доступом"';
        
        $subprocess1 = 'Подпроцесс "Управление учетными записями и правами субъектов логического доступа"';
        $subprocess2 = 'Подпроцесс "Идентификация, аутентификация, авторизация (разграничение доступа) при осуществлении логического доступа"';
        $subprocess3 = 'Подпроцесс "Защита информации при осуществлении физического доступа"';
        $subprocess4 = 'Подпроцесс "Идентификация и учет ресурсов и объектов доступа"';

        $process2 = 'Процесс 2 "Обеспечение защиты вычислительных сетей"';

        $subprocess1_2 = 'Подпроцесс "Сегментация и межсетевое экранирование вычислительных сетей"';
        $subprocess2_2 = 'Подпроцесс "Выявление вторжений и сетевых атак"';
        $subprocess3_2 = 'Подпроцесс "Защита информации, передаваемой по вычислительным сетям"';
        $subprocess4_2 = 'Подпроцесс "Защита беспроводных сетей"';

        $process3 = 'Процесс 3 "Контроль целостности и защищенности информационной инфраструктуры"';

        // Определяем подпроцессы для процесса 3
        $subprocess1_3 = 'Подпроцесс "Контроль уязвимостей и обновлений"';
        $subprocess2_3 = 'Подпроцесс "Контроль целостности и защищенности"';

        $norms = [
            // УЗП.1-УЗП.29
            [
                'code' => 'УЗП.1',
                'description' => 'Осуществление логического доступа пользователями и эксплуатационным персоналом под уникальными и персонифицированными учетными записями',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 1
            ],
            [
                'code' => 'УЗП.2',
                'description' => 'Контроль соответствия фактического состава разблокированных учетных записей фактическому составу легальных субъектов логического доступа',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 2
            ],
            [
                'code' => 'УЗП.3',
                'description' => 'Контроль отсутствия незаблокированных учетных записей: - уволенных работников; - работников, тсутствующих на рабочем месте более 90 календарных дней; - работников внешних (подрядных) организаций, прекративших свою деятельность в организации',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 3
            ],
            [
                'code' => 'УЗП.4',
                'description' => 'Контроль отсутствия незаблокированных учетных записей неопределенного целевого назначения',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 4
            ],
            [
                'code' => 'УЗП.5',
                'description' => 'Документарное определение правил предоставления (отзыва) и блокирования логического доступа',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 5
            ],
            [
                'code' => 'УЗП.6',
                'description' => 'Назначение для всех ресурсов доступа распорядителя логического доступа (владельца ресурса доступа)',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 6
            ],
            [
                'code' => 'УЗП.7',
                'description' => 'Предоставление прав логического доступа по решению распорядителя логического доступа (владельца ресурса доступа)',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 7
            ],
            [
                'code' => 'УЗП.8',
                'description' => 'Хранение эталонной информации о предоставленных правах логического доступа и обеспечение елостности указанной информации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 8
            ],
            [
                'code' => 'УЗП.9',
                'description' => 'Контроль соответствия фактических прав логического доступа эталонной информации о предоставленных правах логического доступа',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 9
            ],
            [
                'code' => 'УЗП.10',
                'description' => 'Исключение возможного бесконтрольного самостоятельного расширения пользователями предоставленных им прав логического доступа',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 10
            ],
            [
                'code' => 'УЗП.11',
                'description' => 'Исключение возможного бесконтрольного изменения пользователями параметров настроек средств и систем защиты информации, параметров настроек АС, связанных с защитой информации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 11
            ],
            [
                'code' => 'УЗП.12',
                'description' => 'Контроль необходимости отзыва прав субъектов логического доступа при изменении их должностных обязанностей',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 12
            ],
            [
                'code' => 'УЗП.13',
                'description' => 'Контроль прекращения предоставления логического доступа и блокирование учетных записей при истечении периода (срока) предоставления логического доступа',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 13
            ],
            [
                'code' => 'УЗП.14',
                'description' => 'Установление фактов неиспользования субъектами логического доступа предоставленных им прав на осуществление логического доступа на протяжении периода времени, превышающего 90 дней',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 14
            ],
            [
                'code' => 'УЗП.16',
                'description' => 'Реализация контроля со стороны распорядителя огического доступа целесообразности дальнейшего предоставления прав логического доступа, не использованных субъектами на протяжении периода времени, указанного в мерах УЗП.14, УЗП.15 настоящей таблицы',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 16
            ],
            [
                'code' => 'УЗП.17',
                'description' => 'Реализация возможности определения состава предоставленных прав логического доступа для онкретного ресурса доступа',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 17
            ],
            [
                'code' => 'УЗП.18',
                'description' => 'Реализация возможности определения состава предоставленных прав логического доступа для конкретного субъекта логического доступа',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 18
            ],
            [
                'code' => 'УЗП.19',
                'description' => 'Определение состава ролей, связанных с выполнением операции (транзакции) в АС, имеющих финансовые последствия для финансовой организации, клиентов и контрагентов, и ролей, связанных с контролем выполнения указанных операций (транзакций), запрет выполнения указанных ролей одним субъектом логического доступа',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 19
            ],
            [
                'code' => 'УЗП.20',
                'description' => 'Реализация правил управления правами логического доступа, обеспечивающих запрет совмещения одним субъектом логического доступа ролей, предусмотренны мерой УЗП.19 настоящей таблицы',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 20
            ],
            [
                'code' => 'УЗП.21',
                'description' => 'Реализация правил управления правами логического доступа, обеспечивающих запрет совмещения одним субъектом логического доступа следующих функций: - эксплуатация и (или) контроль эксплуатации ресурса доступа, в том числе АС, одновременно с использованием по Назначению ресурса доступа в рамках реализации бизнес-процесса финансовой организации; - создание и (или) модернизация ресурса доступа, в том числе АС, одновременно с использованием по Назначению ресурса доступа в рамках реализации бизнес-процесса финансовой организации; - эксплуатация средств и систем защиты информации одновременно с контролем эксплуатации средств и систе защиты информации; - управление учетными записями субъектов логического доступа одновременно с управлением правами субъектов логического доступа',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 21
            ],
            [
                'code' => 'УЗП.22',
                'description' => 'Регистрация событий защиты информации, связанных с действиями, и контроль действий эксплуатационного персонала, обладающего привилегированными правами логич��ского доступа, позволяющими осуществить деструктивное воздействие, приводящие к нарушению выполнения бизнес-процессов или технолоических процессов финансовой организации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 22
            ],
            [
                'code' => 'УЗП.23',
                'description' => 'Регистрация событий защиты информации, связанных с действиями, и ��онтроль действий эксплуатационного персонала и пользователей, обладающих правами ������оги��ес��о��о доступа, в том числе в АС, позволяющими осуществить операции (транзакции), приводящие к финансовым последствиям для финансовой организации, клиентов и контрагентов',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 23
            ],
            [
                'code' => 'УЗП.24',
                'description' => 'Регистрация событий защиты информции, связанных с действиями, и контроль действий эксплуатационного персонала, обладающего правами по управлению логическим доступом',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 24
            ],
            [
                'code' => 'УЗП.25',
                'description' => 'Регистрация событий защиты информации, связанных с действиями по управлению учетными записями и правами субъектов логического доступа',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 25
            ],
            [
                'code' => 'УЗП.26',
                'description' => 'Регистрация событий защиты информаци��, связанных с действиями, и контро��ь действий эксплуатационного персонала, обладающего правам по управлению техническими мерами, реализующими многофакторную аутентификацию',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 26
            ],
            [
                'code' => 'УЗП.27',
                'description' => 'Регистрация событий защиты информации, связанных с действиями, и контроль действий эксплуатационного персонала, обладающего правами по изменению параметров настроек средств и систем защиты информации, параметров настроек АС, связанных с защитой информации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 27
            ],
            [
                'code' => 'УЗП.28',
                'description' => 'Регистрация событий защиты информации, связанных с действиями, и контроль действий эксплуатационного персонала, обладающего правами по управлению криптографическими ключами',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 28
            ],
            [
                'code' => 'УЗП.29',
                'description' => 'Закрепление АРМ пользователей и эксплуатационного персонала за конкретными субъектами логического доступа',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 1,
                'order' => 29
            ],
            
            // РД.1-РД.44
            [
                'code' => 'РД.1',
                'description' => 'Идентификация и однофакторная аутентификация пользователей',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 30
            ],
            [
                'code' => 'РД.2',
                'description' => 'Идентификация и многофакторная аутентификация пользователей',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 31
            ],
            [
                'code' => 'РД.3',
                'description' => 'Идентификация и однофакторная аутентификация эксплуатационного персонала',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 32
            ],
            [
                'code' => 'РД.4',
                'description' => 'Идентификация и многофакторная аутентификация эксплуатационного персонала',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 33
            ],
            [
                'code' => 'РД.5',
                'description' => 'Аутентификация программных сервисов, осуществляющих логический доступ с использованием технических учетных записей',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 34
            ],
            [
                'code' => 'РД.6',
                'description' => 'Аутентификация АРМ эксплуатационного персонала, используемых для осуществления логического доступа',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 35
            ],
            [
                'code' => 'РД.7',
                'description' => 'Аутентификация АРМ пользователей, используемых для осуществления логического доступа',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 36
            ],
            [
                'code' => 'РД.8',
                'description' => 'Сокрытие (неотображение) паролей при их вводе субъектами доступа',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 37
            ],
            [
                'code' => 'РД.9',
                'description' => 'Запрет исп��льзования учетных записей субъектов логического доступа с незаданными аутентификационными данными или заданными по умолчанию разработчиком ресурса доступа, в том числе разработчиком АС',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 38
            ],
            [
                'code' => 'РД.10',
                'description' => 'Запрет на использвание групповых, общих и стандартных учетных записей и паролей, а также прочих подобных методов идентификации и аутентификации, не позволяющих определить конкретного субъекта доступа',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 39
            ],
            [
                'code' => 'РД.11',
                'description' => 'Временная блокировка учетной записи пользователей после выполнения ряда неуспешных последовательных попыток аутентификации на период времени не менее 30 мин',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 40
            ],
            [
                'code' => 'РД.12',
                'description' => 'Запрет множественной аутентификации субъектов логического доступа с использованием одной учетной записи путем открытия параллельных сессий логического доступа с использованием разных АРМ, в том числе виртуальных',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 41
            ],
            [
                'code' => 'РД.13',
                'description' => 'Обеспечение возможности выполнения субъектом логического доступа - работниами финансовой организации процедуры принудительного прерывания сессии логического доступа и (или) приостановки осуществления логического доступа (с прекращением отображения на мониторе АРМ информации, доступ к которой получен в рамках сессии осуществления логического доступа)',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 42
            ],
            [
                'code' => 'РД.14',
                'description' => 'Автоматическое прерывание сессии логического доступа (приостановка осуществления логического доступа) по истечении установленного времени бездействия (неактивности) субъекта логического доступа, не превышающего 15 мин, с прекращением отображения на мониторе АРМ информации, доступ к которой получен в рамках сессии осуществления логического доступа',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 43
            ],
            [
                'code' => 'РД.15',
                'description' => 'Выполнение процедуры повторной аутентификации для продолжения осуществления логического доступа после его принудительного или автоматического прерывания (приостановки осуществления логического доступа), предусмотренного мерами РД.13 и РД.14 настоящей таблицы',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 44
            ],
            [
                'code' => 'РД.16',
                'description' => 'Использование на АРМ субъектов логического доступа встроенных механизмов контроля изменения базовой конфигурации оборудования (пароль на изменение параметров конфигурации системы, хранящихся в энергонезависимой памяти)',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 45
            ],
            [
                'code' => 'РД.17',
                'description' => 'Запрет на использвание технологии аутентификации с сохранением аутентификационных данных в открытом виде в СВТ',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 46
            ],
            [
                'code' => 'РД.18',
                'description' => 'Запрет на передачу аутентификационных данных в открыт��м виде по каналам и линиям связи и их передачу куда-либо, кроме средств или систем аутентификации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 47
            ],
            [
                'code' => 'РД.19',
                'description' => '��мена паролей пользователей не реже одного раза в год',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 48
            ],
            [
                'code' => 'РД.20',
                'description' => 'Смена паролей эксплуатационного персонала не реже одного раза в квартал',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 49
            ],
            [
                'code' => 'РД.21',
                'description' => 'Использование пользователями паролей длиной не менее восьми символов',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 50
            ],
            [
                'code' => 'РД.22',
                'description' => 'Использование эксплуатационным персоналом паролей длиной не менее шестнадцати символов',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 51
            ],
            [
                'code' => 'РД.23',
                'description' => 'Использование при формировании паролей субъектов логического доступа символов, включающих буквы (в верхнем и нижнем регистрах) и цифры',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 52
            ],
            [
                'code' => 'РД.24',
                'description' => 'Запрет использования в качестве паролей субъектов логического доступа легко вычисляемых соче��аний букв и цифр (например, им��на, фамилии, наименования, общепринятые сокращения)',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 53
            ],
            [
                'code' => 'РД.25',
                'description' => 'Обеспечение возможности самостоятельной смены субъектами логического доступа своих паролей',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 54
            ],
            [
                'code' => 'РД.26',
                'description' => 'Хранение копий аутентификационных ��анных эксплуатационного персонала на выделенных МНИ или на бумажных носителях',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 55
            ],
            [
                'code' => 'РД.27',
                'description' => 'Реализация защиты копи аутентификационных данных эксплуатационного персонала от НСД при их хранении на МНИ или бумажных носителях',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 56
            ],
            [
                'code' => 'РД.28',
                'description' => 'Регистрация персонификации, выдачи (передачи) и уничтожения персональных технических устройств аутентификации, реализующих многофакторную аутентификацию',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 57
            ],
            [
                'code' => 'РД.29',
                'description' => 'Смена аутентификационных данных в случае их компрометации',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 58
            ],
            [
                'code' => 'РД.30',
                'description' => 'Авторизация логического доступа к ресурсам доступа, в том числе АС',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 59
            ],
            [
                'code' => 'РД.31',
                'description' => 'Реализация необходимых методов (дискреционный, мандатный, ролевой или иной метод) при разграничении логического доступа к ресурсам доступа',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 60
            ],
            [
                'code' => 'РД.32',
                'description' => 'Реализация ролевого метода (с определением для каждой роли прав доступа) при разграничении логического доступа в АС',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 61
            ],
            [
                'code' => 'РД.33',
                'description' => 'Реализация необходимых типов (чтение, запись, выполнение или иной тип) и правил разграничения логического доступа к ресурсам доступа, в том числе АС',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 62
            ],
            [
                'code' => 'РД.34',
                'description' => 'Запрет реализации ��ользователями бизнес-процессов и технологических процессов финансовой организации с использованием учетных записей эксплуатационного персонала, в том числе в АС',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 63
            ],
            [
                'code' => 'РД.35',
                'description' => 'Запрет выполнения пользователями бизнес-процессов с использованием привилегированных прав логического доступа, в том числе работы пользователей с правами локального администратора АРМ',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 64
            ],
            [
                'code' => 'РД.36',
                'description' => 'Оповещение субъекта логического доступа после успешной авторизации о дате и времени его предыдущей авторизации в АС',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 65
            ],
            [
                'code' => 'РД.37',
                'description' => 'Контроль состава разрешенных действий в АС до выполнения идентификации и аутентификации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 66
            ],
            [
                'code' => 'РД.38',
                'description' => 'Размещение устройств вывода (отображения) информации, исключающее ее н��санкционированный просмотр',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 67
            ],
            [
                'code' => 'РД.39',
                'description' => 'Регистрация выполнения субъектами логического доступа ряда неуспешных последовательных п��пыток аутентификации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 68
            ],
            [
                'code' => 'РД.40',
                'description' => 'Регистрация осуществления субъектами логического доступа идентификации и аутентификации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 69
            ],
            [
                'code' => 'РД.41',
                'description' => 'Регистрация авторизации, завершения и (или) прерывания (приостановки) осуществления эксплуатационным персоналом и пользователями логического доступа, в том числе в АС',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 70
            ],
            [
                'code' => 'РД.42',
                'description' => 'Регистрация запуска программных сервисов, осуществляющих логический доступ',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 71
            ],
            [
                'code' => 'РД.43',
                'description' => 'Регистрация изменений аутентификационных данных, используемых для осуществления логического доступа',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 72
            ],
            [
                'code' => 'РД.44',
                'description' => 'Регистрация действий пользователей и эксплуатационного персонала, предусмотренных в случае компрометации их аутентификационных данных',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 1,
                'order' => 73
            ],

            // ФД.1-ФД.21
            [
                'code' => 'ФД.1',
                'description' => 'Документарное определение правил предоставления физического доступа',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 74
            ],
            [
                'code' => 'ФД.2',
                'description' => 'Контроль перечня лиц, которым предоставлено право самостоятельного физического доступа в помещения',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 75
            ],
            [
                'code' => 'ФД.3',
                'description' => 'Контроль самостоятельного физического доступа в помещения для лиц, не являющихся работниками финансовой организации',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 76
            ],
            [
                'code' => 'ФД.4',
                'description' => 'Контроль самостоятельного физического доступа в помещения для технического (вспомогательного) персонала',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 77
            ],
            [
                'code' => 'ФД.5',
                'description' => 'Осуествление физического доступа лицами, которым не предоставлено право самостоятельного доступа в помещения, только под контролем ра��от��иков финансовой организации, которым предоставлено такое право',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 78
            ],
            [
                'code' => 'ФД.6',
                'description' => 'Назначение для всех помещений распорядителя физического доступа',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 79
            ],
            [
                'code' => 'ФД.7',
                'description' => 'Предоставление права самостоятельного физического доступа в помещения по решению распорядителя физического доступа',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 80
            ],
            [
                'code' => 'ФД.8',
                'description' => 'Оборудование входных дверей помещения механическими замками, обеспечивающими надежное закрытие помещений в нерабочее время',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 81
            ],
            [
                'code' => 'ФД.9',
                'description' => 'Оборудование помещений средствами (системами) контроля и управления доступом',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 82
            ],
            [
                'code' => 'ФД.10',
                'description' => 'Оборудование п��мещений средствами видеонаблюдения',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 83
            ],
            [
                'code' => 'ФД.11',
                'description' => 'Оборудование помещений средствами охранной и пожарной сигнализации',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 84
            ],
            [
                'code' => 'ФД.12',
                'description' => 'Расположение серверного и сетевого оборудования в запираемых серверных стоечных шкафах',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 85
            ],
            [
                'code' => 'ФД.13',
                'description' => 'Контроль доступа к серверному и сетевому оборудованию, расположенному в запираемых серверных стоечных шкафах',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 86
            ],
            [
                'code' => 'ФД.14',
                'description' => 'Хранение архивов информации средств (систем) контроля и управления доступом не менее трех лет',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 87
            ],
            [
                'code' => 'ФД.15',
                'description' => 'Хранение архивов информации средств видеонаблюдения не менее 14 дней*',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 88
            ],
            [
                'code' => 'ФД.16',
                'description' => 'Хранение архивов информации средств видеонаблюдения не менее 90 дней',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 89
            ],
            [
                'code' => 'ФД.17',
                'description' => 'Регистрация доступа к общедоступным объектам доступа с использованием средств видеонаблюдения',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 90
            ],
            [
                'code' => 'ФД.18',
                'description' => 'Хранение архивов информации средств видеонаблюдения, регистрирующих доступ к общедоступным объектам доступа, не менее 14 дней',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 91
            ],
            [
                'code' => 'ФД.19',
                'description' => 'Контроль состояния общедоступных объектов доступа с целью выявлений несанкционированных изменений в их аппаратном обеспечении и (или) ПО',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 92
            ],
            [
                'code' => 'ФД.20',
                'description' => 'Приведение общедоступных объектов доступа, для которых были выявлены несанкционированные изменения в их аппаратном обеспечении и (или) ПО (до устранения указанных несанкционированных изменений), в состояние, при котором невозможно их использование для осуществления операции (транзакции), приводящей к финансовым последствиям для финансовой организации, клиентов и контрагентов',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 93
            ],
            [
                'code' => 'ФД.21',
                'description' => 'Рег��страция событий защиты информации, связанных с входом (выходом) в помещения (из помещений), в которых расположены объекты доступа',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess3,
                'tab' => 1,
                'order' => 94
            ],

            // ИУ.1-ИУ.8
            [
                'code' => 'ИУ.1',
                'description' => 'Учет созданных, используемых и (или) эксплуатируемых ресурсов доступа',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess4,
                'tab' => 1,
                'order' => 95
            ],
            [
                'code' => 'ИУ.2',
                'description' => 'Учет используемых и (или) эксплуатируемых объектов доступа',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess4,
                'tab' => 1,
                'order' => 96
            ],
            [
                'code' => 'ИУ.3',
                'description' => 'Учет эксплуатируемых общедоступных объектов доступа (в том числе банкоматов, платежных терминалов)',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess4,
                'tab' => 1,
                'order' => 97
            ],
            [
                'code' => 'ИУ.4',
                'description' => 'Контроль фактического состава созданных, используемых и (или) эксплуатируемых ресурсов доступа (баз данных, сетевых файловых ресурсов, виртуальных машин) и их корректного размещения в сегментах вычислительных сетей финансовой организации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess4,
                'tab' => 1,
                'order' => 98
            ],
            [
                'code' => 'ИУ.5',
                'description' => 'Контроль выполнения операций по созданию, удалению и резервному копированию ресурсов доступа (баз данных, сетевых файловых ресурсов, ��иртуальных машин)',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess4,
                'tab' => 1,
                'order' => 99
            ],
            [
                'code' => 'ИУ.6',
                'description' => 'Контроль фактического состава эксплуатируемых объектов д��ступа и их корректного размещения в сегментах вычислительных сетей финансовой организации',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess4,
                'tab' => 1,
                'order' => 100
            ],
            [
                'code' => 'ИУ.7',
                'description' => 'Регистрация событий защиты информации, связанных с созданием, копированием, в том ��исле резервным, и (или) удалением ресурсов доступа (баз данных, сетевых файловых ресурсов, виртуальных машин)',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess4,
                'tab' => 1,
                'order' => 101
            ],
            [
                'code' => 'ИУ.8',
                'description' => 'Регистрация событий защиты информации, связанных с подключением (регистрацией) объектов доступа в вычислительных сетях финансовой организации',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess4,
                'tab' => 1,
                'order' => 102
            ],

            // СМЭ.1-СМЭ.21
            [
                'code' => 'СМЭ.1',
                'description' => 'Выделение в вычислительных сетях финансовой организации отдельных сегментов (групп сегментов), предназначенных для размещения информационной инфраструктуры каждого из контуров безопасности (далее - сегменты контуров безопасности)',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 103
            ],
            [
                'code' => 'СМЭ.2',
                'description' => 'Реализация сетевого взаимодействия и сетевой изоляции на уровне не выше третьего (сетевой) по семиуровневой стандартной модели взаимодействия открытых систем, определенной в ГОСТ Р ИСО/МЭК 7498-1, сегментов контуров безопасности и внутренних вычислительных сетей финансовой организации, не предназначенных для размещения информационной инфраструктуры, входящей в контуры безопасности (далее - иные внутренние вычислительные сети финансовой организации)',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 104
            ],
            [
                'code' => 'СМЭ.3',
                'description' => 'Межсетевое экранирование вычислительных сетей сегментов контуров безопасности, включая фильтрацию данных на сетевом и прикладном уровнях семиуровневой стандартной модели взаимодействия открытых систем, определенной в ГОСТ Р ИСО/МЭК 7498-1',
                'implementation_type' => 'Т', 
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 105
            ],
            [
                'code' => 'СМЭ.4',
                'description' => 'Реализация и контроль информационного взаимодействия между сегментами контуров безопасности и иными внутренними вычислительными сетями финансовой организации в соответствии с установленными правилами и протоколами сетевого взаимодействия',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 106
            ],
            [
                'code' => 'СМЭ.5',
                'description' => 'Реализация и контроль информационного взаимодействия с применением программных шлюзов между сегментами контуров безопасности и иными внутренними вычислительными сетями финансовой организации с целью обеспечения ограничения и контроля на передачу данных по инициативе субъектов логического доступа',
                'implementation_type' => 'Н',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 107
            ],
            [
                'code' => 'СМЭ.6',
                'description' => 'Выделение в вычислительных сетях финансовой организации отдельных сегментов (групп сегментов), предназначенных для размещения информационной инфраструктуры, используемой только на этапе создания и (или) модернизации АС, в том числе тестирования ПО и СВТ (далее - сегмент разработки и тестирования)',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 108
            ],
            [
                'code' => 'СМЭ.7',
                'description' => 'Реализация запрета сетевого взаимодействия сегмента разработки и тестирования и иных внутренних вычислительных сетей финансовой организации по инициативе сегмента разработки и тестирования',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 109
            ],
            [
                'code' => 'СМЭ.8',
                'description' => 'Выделение в составе сегментов контуров безопасности отдельных пользовательских сегментов, в которых располагаются только АРМ пользователей',
                'implementation_type' => 'Н',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 110
            ],
            [
                'code' => 'СМЭ.9',
                'description' => 'Выделен��е в составе сегментов контуров безопасности отдельных сегментов управления, в которых располагаются только АРМ эксплуатационного персонала, используемые для выполнения задач администрирования информационной инфраструктуры',
                'implementation_type' => 'Н',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 111
            ],
            [
                'code' => 'СМЭ.10',
                'description' => 'Выделение в составе сегментов контуров безопасности отдельных сегментов хранения и обработки данных, в которых располагаются ресурсы доступа, предназначенные для обработки и хранения данных, серверное оборудование и системы хранения данных',
                'implementation_type' => 'Н',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 112
            ],
            [
                'code' => 'СМЭ.11',
                'description' => 'Выделение отдельных сегментов для размещения общедоступных объектов доступа (в том числе банкоматов, платежных терминалов)',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 113
            ],
            [
                'code' => 'СМЭ.12',
                'description' => 'Реализация и контроль информационного взаимодействия между сегментами вычислительных сетей, определенных мерами СМЭ.8-СМЭ.11 настоящей таблицы, и иными сегментами вычислительных сетей в соответствии с установленными правилами и протоколами сетевого взаимодействия',
                'implementation_type' => 'Н',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 114
            ],
            [
                'code' => 'СМЭ.13',
                'description' => 'Контроль содержимого информации при ее переносе из сегментов или в сегменты контуров безопасности с использованием переносных (отчуждаемых) носителей информации',
                'implementation_type' => 'О',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 115
            ],
            [
                'code' => 'СМЭ.14',
                'description' => 'Реализация сетевого взаимодействия и сетевой изоляции на уровне не выше второго (канальный) по семиуровневой стандартной модели взаимодействия открытых систем, определенной в ГОСТ Р ИСО/МЭК 7498-1, внутренних вычислительных сетей финансовой организации и сети Интернет',
                'implementation_type' => 'Н',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 116
            ],
            [
                'code' => 'СМЭ.15',
                'description' => 'Реализация сетевого взаимодействия и сетевой изоляции на уровне не выше третьего (сетевой) по семиуровневой стандартной модели взаимодействия открытых систем, определенной в ГОСТ Р ИСО/МЭК 7498-1, внутренних вычислительных сетей финансовой организации и сегментов вычисленных сетей, выделенных в соответствии с мерой ЗБС.3 настоящей таблицы',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 117
            ],
            [
                'code' => 'СМЭ.16',
                'description' => 'Межсетевое экранирование внутренних вычислительных сетей финансовой организации и сегментов вычисленных сетей, выделенных в соответствии с мерой ЗБС.3 настоящей таблицы, включая фильтрацию данных на сетевом и прикладном уровнях семиуровневой стандартной модели взаимодействия открытых систем, определенной в ГОСТ Р ИСО/МЭК 7498-1',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 118
            ],
            [
                'code' => 'СМЭ.17',
                'description' => 'Реализация и контроль информационного взаимодействя внутренних вычислтельных сетей финансовой организации и сегментов вычисленных сетей, выделенных в соответствии с мерой ЗБС.3 таблицы 20, в соответствии с установленными правилами и протоколами сетевого взаимодействия',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 119
            ],
            [
                'code' => 'СМЭ.18',
                'description' => 'Сокрытие топологии внутренних вычислительных сетей финансовой организации',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 120
            ],
            [
                'code' => 'СМЭ.19',
                'description' => 'Реализация сетевого взаимодействия внутренних вычислительных сетей финансовой организации и сети Интернет через ограниченное количество контролируемых точек доступа',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 121
            ],
            [
                'code' => 'СМЭ.20',
                'description' => 'Реализация почтового обмена с сетью Интернет через ограниченное количество контролируемых точек информационного взаимодействия, состоящих из внешнего (подключенного к сети Интернет) и внутреннего (размещенного во внутренних сетях финансовой организации) почтовых серверов с безопасной репликацией почтовых сообщений между ними',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 122
            ],
            [
                'code' => 'СМЭ.21',
                'description' => 'Регистрация изменений параметров настроек средств и систем защиты информации, обеспечивающих реализацию сегментации, межсетевого экранирования и защиты вычислительных сетей финансовой организации',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess1_2,
                'tab' => 2,
                'order' => 123
            ],

            // Добавляю нормы ВСА.2-ВСА.14
            [
                'code' => 'ВСА.2',
                'description' => 'Контроль отсутствия (выявление) аномальной сетевой активности, связанной с возможным несанкционированным информационным взаимодействием между вычислительными сетями финансовой организации и сетью Интернет',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess2_2,
                'tab' => 2,
                'order' => 125
            ],
            [
                'code' => 'ВСА.3',
                'description' => 'Контроль отсутствия (выявление) аномальной сетевой активности, связанной с возможным несанкционированным информационным взаимодействием между сегментами, предназначенными для размещения общедоступных объектов доступа (в том числе банкоматов, платежных терминалов), и сетью Интернет',
                'implementation_type' => 'Н',
                'process_name' => $process2,
                'subprocess_name' => $subprocess2_2,
                'tab' => 2,
                'order' => 126
            ],
            [
                'code' => 'ВСА.4',
                'description' => 'Контроль отсутствия (выявление) аномальной сетевой активности, связанной с возможным несанкционированным логическим доступом к ресурсам доступа, размещенным в вычислительных сетях финансовой организации, подключенных к сети Интернет',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess2_2,
                'tab' => 2,
                'order' => 127
            ],
            [
                'code' => 'ВСА.5',
                'description' => 'Контроль отсутствия (выявление) аномальной сетевой активности, связанной с возможным несанкционированным удаленным доступом',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess2_2,
                'tab' => 2,
                'order' => 128
            ],
            [
                'code' => 'ВСА.6',
                'description' => 'Контроль отсутствия (выявление) аномальной сетевой активности, связанной с возможным несанкционированным логическим доступом к ресурсам доступа, размещенным во внутренних вычислительных сетях финансовой организации',
                'implementation_type' => 'Н',
                'process_name' => $process2,
                'subprocess_name' => $subprocess2_2,
                'tab' => 2,
                'order' => 129
            ],
            [
                'code' => 'ВСА.7',
                'description' => 'Контроль отсутствия (выявление) аномальной сетевой активности, связанной с возможным несанкционированным доступом к аутентификационным данным легальных субъектов доступа',
                'implementation_type' => 'Н',
                'process_name' => $process2,
                'subprocess_name' => $subprocess2_2,
                'tab' => 2,
                'order' => 130
            ],
            [
                'code' => 'ВСА.8',
                'description' => 'Контроль отсутствия (выявление) аномальной сетевой активности, связанной с возможным осуществлением атак типа "отказ в обслуживании", предпринимаемых в отношении ресурсов доступа, размещенных в вычислительных сетях финансовой организации, подключенных к сети Интернет',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess2_2,
                'tab' => 2,
                'order' => 131
            ],
            [
                'code' => 'ВСА.9',
                'description' => 'Блокирование атак типа "отказ в обслуживании" в масштабе ����ремени, близком к реальному',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess2_2,
                'tab' => 2,
                'order' => 132
            ],
            [
                'code' => 'ВСА.10',
                'description' => 'Контроль и обеспечение возможности блокировки нежелательных сообщений электронной почты (SPAM)',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess2_2,
                'tab' => 2,
                'order' => 133
            ],
            [
                'code' => 'ВСА.11',
                'description' => 'Реализация контроля, предусмотренного мерами ВСА.1-ВСА.9 настоящей таблицы, путем сканирования и анализа сетевого трафика между группами сегментов вычислительных сетей финансовой организации, входящих в разные контуры безопасности',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess2_2,
                'tab' => 2,
                'order' => 134
            ],
            [
                'code' => 'ВСА.12',
                'description' => 'Реализация контроля, предусмотренного мерами ВСА.1-ВСА.9 настоящей таблицы, путем сканирования и анализа сетевого трафика в пределах сегмента контура безопасности',
                'implementation_type' => 'Н',
                'process_name' => $process2,
                'subprocess_name' => $subprocess2_2,
                'tab' => 2,
                'order' => 135
            ],
            [
                'code' => 'ВСА.13',
                'description' => 'Реализация контроля, предусмотренного мерами ВСА.1-ВСА.9 настоящей таблицы, путем сканирования и анализа сетевого трафика между вычислительными сетями финансовой организации и сетью Интернет',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess2_2,
                'tab' => 2,
                'order' => 136
            ],
            [
                'code' => 'ВСА.14',
                'description' => 'Регистрация фактов выявления аномальной сетевой активности в рамках контроля, предусмотренного мерами ВСА.1-ВСА.8 таблицы 16',
                'implementation_type' => 'Т',
                'process_name' => $process2,
                'subprocess_name' => $subprocess2_2,
                'tab' => 2,
                'order' => 137
            ],

            // Процесс 3 - Контроль целостности и защищенности информационной инфраструктуры
            [
                'code' => 'ЦЗИ.1',
                'description' => 'Контроль отсутствия и обеспечение оперативного устранения известных (описанных) уязвимостей защиты информации, использование которых может позволить осуществить несанкционированное (неконтролируемое) информационное взаимодействие между сегментами контуров безопасности и иными внутренними сетями финансовой организации',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 150
            ],
            [
                'code' => 'ЦЗИ.2',
                'description' => 'Контроль отсутствия и обеспечение оперативного устранения известных (описанных) уязвимостей защиты информации, использование которых может позволить осуществить несанкционированное (неконтролируемое) информационное взаимодействие между внутренними вычислительными сетями финансовой организации и сетью Интернет',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 151
            ],
            [
                'code' => 'ЦЗИ.3',
                'description' => 'Контроль отсутствия и обеспечение оперативного устранения известных (описанных) уязвимостей защиты информации, использование которых может позволить осуществить несанкционированное (неконтролируемое) информационное взаимодействие между сегментами, предназначенными для размещения общедоступных объектов доступа (в том числе банкоматов, платежных терминалов), и сетью Интернет',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 152
            ],
            [
                'code' => 'ЦЗИ.4',
                'description' => 'Контроль отсутствия и обеспечение оперативного устранения известных (описанных) уязвимостей защиты информации, использование которых может позволить осуществить несанкционированный логический доступ к ресурсам доступа, размещенным в вычислительных сетях финансовой организации, подключенных к сети Интернет',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 153
            ],
            [
                'code' => 'ЦЗИ.5',
                'description' => 'Контроль отсутствия и обеспечение оперативного устранения известных (описанных) уязвимостей защиты информации, использование которых может позволить осуществить несанкционированный удаленный доступ',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 154
            ],
            [
                'code' => 'ЦЗИ.6',
                'description' => 'Контроль отсутствия и обеспечение оперативного устранения известных (описанных) уязвимостей защиты информации, использование которых может позволить осуществить несанкционированный логический доступ к ресурсам доступа, размещенным во внутренних вычислительных сетях финансовой организации',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 155
            ],
            [
                'code' => 'ЦЗИ.7',
                'description' => 'Контроль отсутствия и обеспечение оперативного устранения известных (описанных) уязвимостей, предусмотренных мерами ЦЗИ.1-ЦЗИ.6 настоящей таблицы, путем сканирования и анализа параметров настроек серверного и сетевого оборудования',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 156
            ],
            [
                'code' => 'ЦЗИ.8',
                'description' => 'Контроль отсутствия и обеспечение оперативного устранения известных (описанных) уязвимостей, указанных в пунктах ЦЗИ.1-ЦЗИ.6 настоящей таблицы, путем сканирования и анализа состава, версий и параметров настроек прикладного ПО, ПО АС и системного ПО, реализующего функции обеспечения защиты информации и (или) влияющего на обеспечение защиты информации (далее в настоящем разделе - системное ПО)*, установленного на серверном и сетевом оборудовании',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 157
            ],
            [
                'code' => 'ЦЗИ.9',
                'description' => 'Контроль отсутствия и обеспечение оперативного устранения известных (описанных) уязвимостей, предусмотренных мерами ЦЗИ.1-ЦЗИ.6 настоящей таблицы, путем сканирования и анализа состава, версий и параметров настроек прикладного ПО, ПО АС и (или) системного ПО, установленного на АРМ пользователей и эксплуатационного персонала',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 158
            ],
            [
                'code' => 'ЦЗИ.10',
                'description' => 'Контроль отсутствия и обеспечение оперативного устранения известных (описанных) уязвимостей, предусмотренных мерами ЦЗИ.1-ЦЗИ.6 настоящей таблицы, путем сканирования и анализа состава, версий и параметров настроек средств и систем защиты информации',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 159
            ],
            [
                'code' => 'ЦЗИ.11',
                'description' => 'Ограничение (запрет) использования на АРМ пользователей и эксплуатационного персонала, задействованных в выполнении бизнес-процессов финансовой организации, ПО, реализующего функции по разработке, отладке и (или) тестированию ПО',
                'implementation_type' => 'О',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 160
            ],
            [
                'code' => 'ЦЗИ.12',
                'description' => 'Контроль размещения и своевременного обновления на серверном и сетевом оборудовании ПО средств и систем защиты информации, прикладного ПО, ПО АС, системного ПО и сигнатурных баз средств защиты информации, в том числе с целью устранения выявленных уязвимостей защиты информации',
                'implementation_type' => 'О',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 161
            ],
            [
                'code' => 'ЦЗИ.13',
                'description' => 'Контроль размещения и своевременного обновления на АРМ пользователей и эксплуатационного персонала ПО средств и систем защиты информации, прикладного ПО, ПО АС и системного ПО, в том числе с целью устранения выявленных уязвимостей защиты информации',
                'implementation_type' => 'О',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 162
            ],
            [
                'code' => 'ЦЗИ.14',
                'description' => 'Контроль работоспособности (тестирование) и правильности функционирования АС после выполнения обновлений ПО, предусмотренного мерами ЦЗИ.12 и ЦЗИ.13 настоящей таблицы, выполняемого в сегментах разработки и тестирования',
                'implementation_type' => 'О',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 163
            ],
            [
                'code' => 'ЦЗИ.15',
                'description' => 'Контроль отсутствия и обеспечение оперативного устранения известных (описанных) уязвимостей защиты информации после выполнения обновлений ПО, предусмотренного мерой ЦЗИ.12 настоящей таблицы',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 164
            ],
            [
                'code' => 'ЦЗИ.16',
                'description' => 'Обеспечение возможности восстановления эталонных копий ПО АС, ПО средств и систем защиты информации, системного ПО в случаях нештатных ситуаций',
                'implementation_type' => 'О',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 165
            ],
            [
                'code' => 'ЦЗИ.17',
                'description' => 'Наличие, учет и контроль целостности эталонных копий ПО АС, ПО средств и систем защиты информации, системного ПО',
                'implementation_type' => 'Н',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 166
            ],
            [
                'code' => 'ЦЗИ.18',
                'description' => 'Наличие, учет и контроль целостности эталонных значений параметров настроек ПО АС, системного ПО, ПО средств и систем защиты информации, возможность восстановления указанных настроек в случаях нештатных ситуаций',
                'implementation_type' => 'О',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 167
            ],
            [
                'code' => 'ЦЗИ.19',
                'description' => 'Контроль целостности и достоверности источников получения при распространении и (или) обновлении ПО АС, ПО средств и систем защиты информации, системного ПО',
                'implementation_type' => 'О',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 168
            ],
            [
                'code' => 'ЦЗИ.20',
                'description' => 'Контроль состава разрешенного для использования ПО АРМ пользователей и эксплуатационного персонала',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 169
            ],
            [
                'code' => 'ЦЗИ.21',
                'description' => 'Исключение возможности установки и (или) запуска неразрешенного для использования ПО АРМ пользователей и эксплуатационного персонала',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 170
            ],
            [
                'code' => 'ЦЗИ.22',
                'description' => 'Контроль состава ПО серверного оборудования',
                'implementation_type' => 'О',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 171
            ],
            [
                'code' => 'ЦЗИ.23',
                'description' => 'Контроль состава ПО АРМ пользователей и эксплуатационного персонала, запускаемого при загрузке операционной системы',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 172
            ],
            [
                'code' => 'ЦЗИ.24',
                'description' => 'Контроль целостности запускаемых компонентов ПО АС на АРМ пользователей и эксплуатационного персонала',
                'implementation_type' => 'Н',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 173
            ],
            [
                'code' => 'ЦЗИ.25',
                'description' => 'Реализация доверенной загрузки операционных систем АРМ пользователей и эксплуатационного персонала',
                'implementation_type' => 'Н',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 174
            ],
            [
                'code' => 'ЦЗИ.26',
                'description' => 'Контроль (выявление) использования технологии мобильного кода',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 175
            ],
            [
                'code' => 'ЦЗИ.27',
                'description' => 'Регистрация фактов выявления уязвимостей защиты информации',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 176
            ],
            [
                'code' => 'ЦЗИ.28',
                'description' => 'Регистрация установки, обновления и (или) удаления ПО АС, ПО средств и систем защиты информации, системного ПО на серверном и сетевом оборудовании',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 177
            ],
            [
                'code' => 'ЦЗИ.29',
                'description' => 'Регистрация установки, обновления и (или) удаления прикладного ПО, ПО АС, ПО средств и систем защиты информации, системного ПО на АРМ пользователей и эксплуатационного персонала',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 178
            ],
            [
                'code' => 'ЦЗИ.30',
                'description' => 'Регистрация запуска программных сервисов',
                'implementation_type' => 'Н',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 179
            ],
            [
                'code' => 'ЦЗИ.31',
                'description' => 'Регистрация результатов выполнения операций по контролю состава ПО серверного оборудования, АРМ пользователей и эксплуатационного персонала',
                'implementation_type' => 'Н',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 180
            ],
            [
                'code' => 'ЦЗИ.32',
                'description' => 'Регистрация результатов выполнения операций по контролю состава ПО АРМ пользователей и эксплуатационного персонала',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 181
            ],
            [
                'code' => 'ЦЗИ.33',
                'description' => 'Регистрация результатов выполнения операций по контролю состава ПО, запускаемого при загрузке операционной системы АРМ пользователей и эксплуатационного персонала',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 182
            ],
            [
                'code' => 'ЦЗИ.34',
                'description' => 'Регистрация результатов выполнения операций контроля целостности запускаемых компонентов ПО АС',
                'implementation_type' => 'Н',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 183
            ],
            [
                'code' => 'ЦЗИ.35',
                'description' => 'Регистрация выявления использования технологии мобильного кода',
                'implementation_type' => 'Т',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 184
            ],
            [
                'code' => 'ЦЗИ.36',
                'description' => 'Регистрация результатов выполнения операций по контролю целостности и достоверности источников получения при распространении и (или) обновлении ПО АС, ПО средств и систем защиты информации, системного ПО',
                'implementation_type' => 'Н',
                'process_name' => $process3,
                'subprocess_name' => $subprocess1_3,
                'tab' => 3,
                'order' => 185
            ]
        ];

        foreach ($norms as $norm) {
            DB::table('norms')->insert($norm);
        }
    }
}