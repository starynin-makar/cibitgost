<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Organization;
use App\Models\Norm;
use App\Models\Process;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\NormAssessment;
use App\Models\Evidence;
use App\Models\ProcessEvaluation;
use App\Models\ProcessScore;
use App\Models\NormDocument;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Access;
use App\Models\Assessment;

class AuditController extends Controller
{
    public function index(Organization $organization)
    {
        $user = auth()->user();
        
        if (!$user->is_admin) {
            $hasAccess = Access::where('user_id', $user->id)
                ->where('organization_id', $organization->id)
                ->exists();
            
            if (!$hasAccess) {
                return redirect()->route('dashboard')
                    ->with('error', 'У вас нет доступа к аудитам этой организации');
            }
            
            // Получаем только аудиты, к которым у пользователя есть доступ
            $audits = $organization->audits()
                ->whereHas('accesses', function($query) use ($user) {
                    $query->whereHas('user', function($q) use ($user) {
                        $q->where('id', $user->id);
                    });
                })
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Администратор видит все аудиты организации
            $audits = $organization->audits()
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('audits.index', compact('organization', 'audits'));
    }

    public function create(Organization $organization)
    {
        return view('audits.create', compact('organization'));
    }

    public function store(Request $request, Organization $organization)
    {
        try {
            DB::beginTransaction();

            // Валидация
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|string|in:planned,in_progress,completed,cancelled',
            ]);

            // Добавляем даты автоматически
            $validated['start_date'] = now();
            $validated['end_date'] = now()->addMonths(3);
            $validated['user_id'] = auth()->id();
            $validated['organization_id'] = $organization->id;

            // Создаем аудит
            $audit = Audit::create($validated);

            // Создаем все 8 процессов для аудита
            $processes = [
                1 => 'Процесс 1 "Обеспечение защиты информации при управлении доступом"',
                2 => 'Процесс 2 "Обеспечение защиты вычислительных сетей"',
                3 => 'Процесс 3 "Контроль целостности и защищенности информационной инфраструктуры"',
                4 => 'Процесс 4 "Защита от вредоносного кода"',
                5 => 'Процесс 5 "Предотвращение утечек информации"',
                6 => 'Процесс 6 "Управление инцидентами защиты информации"',
                7 => 'Процесс 7 "Защита среды виртуализации"',
                8 => 'Процесс 8 "Защита информации при осуществлении удаленного логического доступа"'
            ];

            foreach ($processes as $number => $name) {
                $audit->processes()->create([
                    'number' => $number,
                    'name' => $name,
                    'technical_score' => 'н/о',
                    'planning_score' => 'н/о',
                    'implementation_score' => 'н/о',
                    'control_score' => 'н/о',
                    'improvement_score' => 'н/о',
                    'qualitative_score' => 'н/о',
                    'numerical_score' => 'н/о',
                    'violations_count' => 0
                ]);
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Аудит успешно создан',
                    'audit' => $audit
                ]);
            }

            return redirect()->route('organizations.audits.index', $organization)
                ->with('success', 'Аудит успешно создан');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating audit: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Ошибка при создании аудита'
                ], 500);
            }

            return back()->with('error', 'Ошибка при создании аудита');
        }
    }

    public function update(Request $request, Organization $organization, Audit $audit)
    {
        if ($audit->user_id !== auth()->id()) {
            return redirect()->route('organizations.audits.index', $organization)
                ->with('error', 'У вас нет прав на редактирование этого аудита');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,completed'
        ]);

        if (!isset($validated['status'])) {
            $validated['status'] = 'pending';
        }

        if ($validated['status'] === 'completed' && $audit->status !== 'completed') {
            $validated['end_date'] = now();
        }

        $audit->update($validated);

        return redirect()->route('organizations.audits.index', $organization)
            ->with('success', 'Аудит успешно обновлен');
    }

    public function show(Organization $organization, Audit $audit)
    {
        // Проверяем доступ к аудиту
        $user = auth()->user();
        if (!$user->is_admin) {
            $hasAccess = Access::where('user_id', $user->id)
                ->where('organization_id', $organization->id)
                ->exists();
            
            if (!$hasAccess) {
                return redirect()->route('dashboard')
                    ->with('error', 'У вас нет доступа к этому аудиту');
            }
        }

        return view('audits.show', compact('organization', 'audit'));
    }

    public function edit(Organization $organization, Audit $audit)
    {
        if (request()->ajax()) {
            return response()->json([
                'id' => $audit->id,
                'title' => $audit->title,
                'description' => $audit->description,
                'status' => $audit->status
            ]);
        }

        return view('audits.edit', compact('organization', 'audit'));
    }

    public function conduct(Organization $organization, Audit $audit)
    {
        // Получаем информацию о заполненных вкладках
        $completedTabsInfo = $audit->getCompletedTabs();
        
        // Получаем номер последней просмотренной вкладки из запроса
        $lastVisitedTab = request('lastVisitedTab');
        
        // Определяем вкладку для перенаправления:
        // 1. Если есть последняя просмотренная вкладка, используем ее
        // 2. Если есть заполненные вкладки, перенаправляем на следующую после последней заполненной
        // 3. Если нет заполненных вкладок, перенаправляем на первую вкладку
        $tabToRedirect = 1; // По умолчанию первая вкладка
        
        if ($lastVisitedTab) {
            // Если у нас есть информация о последней посещенной вкладке, используем ее
            $tabToRedirect = $lastVisitedTab;
        } else if ($completedTabsInfo['last_completed_tab'] > 0) {
            // Перенаправляем на следующую вкладку после последней заполненной
            $tabToRedirect = $completedTabsInfo['last_completed_tab'] + 1;
            
            // Если следующая вкладка не существует или это последняя вкладка,
            // перенаправляем на последнюю заполненную
            $totalTabs = count($this->getTabs());
            if ($tabToRedirect > $totalTabs) {
                $tabToRedirect = $completedTabsInfo['last_completed_tab'];
            }
        }
        
        return redirect()->route('audit.process', [
            'audit' => $audit->id,
            'process' => 1, // Значение по умолчанию
            'tab' => $tabToRedirect,
            'lastVisitedTab' => $lastVisitedTab // Передаем информацию о последней посещенной вкладке
        ]);
    }

    public function printTab(Audit $audit, $process, $tab)
    {
        try {
            $norms = Norm::with(['assessments' => function($query) use ($audit) {
                $query->where('audit_id', $audit->id);
            }])
            ->where('tab', $tab)
            ->orderBy('process_name')
            ->orderBy('subprocess_name')
            ->orderBy('order')
            ->get();

            $groupedNorms = $norms->groupBy('process_name')->map(function ($processNorms) {
                return $processNorms->groupBy('subprocess_name');
            });
            
            // Рассчитываем оценки для каждого процесса
            $scores = [];
            foreach ($groupedNorms as $processName => $subprocesses) {
                $allScores = collect();
                foreach ($subprocesses as $subprocess) {
                    foreach ($subprocess as $norm) {
                        $score = $norm->assessments->first()?->score;
                        if ($score !== null) {
                            $allScores->push($score);
                        }
                    }
                }
                
                if ($allScores->isNotEmpty()) {
                    $avg = $allScores->avg();
                    $scores[$processName] = [
                        'numerical' => number_format($avg, 2),
                        'qualitative' => $this->getQualitativeScore($avg)
                    ];
                }
            }

            return view('audits.print.tab', [
                'audit' => $audit,
                'groupedNorms' => $groupedNorms,
                'scores' => $scores
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in printTab: ' . $e->getMessage());
            return back()->with('error', 'Ошибка при формировании печатной формы');
        }
    }

    public function printList(Audit $audit, $process, $tab)
    {
        try {
            // Получаем нормы с документами и свидетельствами для конкретного процесса
            $norms = Norm::with(['assessments' => function($query) use ($audit) {
                $query->where('audit_id', $audit->id);
            }])
            ->where('tab', $tab)
            ->where('process_name', 'like', "Процесс {$process}%") // Фильтруем по номеру процесса
            ->orderBy('subprocess_name')
            ->orderBy('order')
            ->get();

            // Получаем название процесса
            $processName = $norms->first()?->process_name ?? "Процесс {$process}";

            return view('audits.print.list', [
                'processName' => $processName,
                'norms' => $norms,
                'audit' => $audit,
                'process' => $process,
                'tab' => $tab
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in printList: ' . $e->getMessage());
            return back()->with('error', 'Ошибка при формировании списка для печати');
        }
    }

    public function process(Audit $audit, $process, $tab)
    {
        $tabs = $this->getTabs();
        
        // Получаем информацию о заполненных вкладках
        $completedTabsInfo = $audit->getCompletedTabs();
        
        // Получаем номер последней просмотренной вкладки из запроса и передаем в представление
        $lastVisitedTab = request('lastVisitedTab');
        
        // Проверяем, запрошен ли список для печати
        if (request()->has('list')) {
            try {
                // Получаем нормы с документами и свидетельствами
                $norms = Norm::with(['evidence' => function($query) use ($audit) {
                    $query->where('audit_id', $audit->id)
                          ->with('user');
                }])
                ->where('tab', $tab)
                ->orderBy('subprocess_name')
                ->orderBy('order')
                ->get();

                // Получаем название процесса
                $processName = $norms->first()?->process_name ?? 'Процесс не найден';

                // Группируем нормы по подпроцессам
                $groupedNorms = $norms->groupBy('subprocess_name');

                return view('audits.print.list', [
                    'processName' => $processName,
                    'groupedNorms' => $groupedNorms,
                    'audit' => $audit
                ]);
            } catch (\Exception $e) {
                \Log::error('Error in printList: ' . $e->getMessage());
                return back()->with('error', 'Ошибка при формировании списка для печати: ' . $e->getMessage());
            }
        }

        // Если это вкладка B14 Оценки
        if ($tab == 15) {
            // Загружаем существующие оценки из БД
            $processScores = ProcessScore::where('audit_id', $audit->id)->get();
            
            // Полный список процессов
            $scores = [
                'Процесс 1 "Обеспечение защиты информации при управлении доступом"' => [
                    'technical_score' => 'н/о',
                    'planning_score' => 'н/о',
                    'implementation_score' => 'н/о',
                    'control_score' => 'н/о',
                    'improvement_score' => 'н/о',
                    'qualitative_score' => 'н/о',
                    'numerical_score' => 'н/о'
                ],
                'Процесс 2 "Обеспечение защиты вычислительных сетей"' => [
                    'technical_score' => 'н/о',
                    'planning_score' => 'н/о',
                    'implementation_score' => 'н/о',
                    'control_score' => 'н/о',
                    'improvement_score' => 'н/о',
                    'qualitative_score' => 'н/о',
                    'numerical_score' => 'н/о'
                ],
                'Процесс 3 "Контроль целостности и защищенности информационной инфраструктуры"' => [
                    'technical_score' => 'н/о',
                    'planning_score' => 'н/о',
                    'implementation_score' => 'н/о',
                    'control_score' => 'н/о',
                    'improvement_score' => 'н/о',
                    'qualitative_score' => 'н/о',
                    'numerical_score' => 'н/о'
                ],
                'Процесс 4 "Защита от вредоносного кода"' => [
                    'technical_score' => 'н/о',
                    'planning_score' => 'н/о',
                    'implementation_score' => 'н/о',
                    'control_score' => 'н/о',
                    'improvement_score' => 'н/о',
                    'qualitative_score' => 'н/о',
                    'numerical_score' => 'н/о'
                ],
                'Процесс 5 "Предотвращение утечек информации"' => [
                    'technical_score' => 'н/о',
                    'planning_score' => 'н/о',
                    'implementation_score' => 'н/о',
                    'control_score' => 'н/о',
                    'improvement_score' => 'н/о',
                    'qualitative_score' => 'н/о',
                    'numerical_score' => 'н/о'
                ],
                'Процесс 6 "Управление инцидентами защиты информации"' => [
                    'technical_score' => 'н/о',
                    'planning_score' => 'н/о',
                    'implementation_score' => 'н/о',
                    'control_score' => 'н/о',
                    'improvement_score' => 'н/о',
                    'qualitative_score' => 'н/о',
                    'numerical_score' => 'н/о'
                ],
                'Процесс 7 "Защита среды виртуализации"' => [
                    'technical_score' => 'н/о',
                    'planning_score' => 'н/о',
                    'implementation_score' => 'н/о',
                    'control_score' => 'н/о',
                    'improvement_score' => 'н/о',
                    'qualitative_score' => 'н/о',
                    'numerical_score' => 'н/о'
                ],
                'Процесс 8 "Защита информации при осуществлении удаленного логического доступа"' => [
                    'technical_score' => 'н/о',
                    'planning_score' => 'н/о',
                    'implementation_score' => 'н/о',
                    'control_score' => 'н/о',
                    'improvement_score' => 'н/о',
                    'qualitative_score' => 'н/о',
                    'numerical_score' => 'н/о'
                ]
            ];

            // Заполняем сохраненными значениями
            foreach ($processScores as $savedScore) {
                if (isset($scores[$savedScore->process_name])) {
                    $scores[$savedScore->process_name] = [
                        'technical_score' => $savedScore->technical_score ?? 'н/о',
                        'planning_score' => $savedScore->planning_score ?? 'н/о',
                        'implementation_score' => $savedScore->implementation_score ?? 'н/о',
                        'control_score' => $savedScore->control_score ?? 'н/о',
                        'improvement_score' => $savedScore->improvement_score ?? 'н/о',
                        'qualitative_score' => $savedScore->qualitative_score ?? 'н/о',
                        'numerical_score' => $savedScore->numerical_score ?? 'н/о'
                    ];
                }
            }

            return view('audits.itogo', [
                'organization' => $audit->organization,
                'audit' => $audit,
                'process' => $process,
                'tabs' => $tabs,
                'tab' => $tab,
                'scores' => $scores,
                'lifecycle_score' => $this->calculateLifecycleScore($processScores),
                'violations_count' => $this->calculateViolationsCount($audit->id),
                'final_score' => $this->calculateFinalScore(
                    $this->calculateLifecycleScore($processScores), 
                    $this->calculateViolationsCount($audit->id)
                ),
                'completedTabsInfo' => $completedTabsInfo,
                'lastVisitedTab' => $lastVisitedTab
            ]);
        }

        // Если это вкладка НЗИ (Z)
        if ($tab == 14) {
            // Получаем нормы для вкладки НЗИ с их оценками
            $norms = Norm::with(['assessments' => function($query) use ($audit) {
                $query->where('audit_id', $audit->id);
            }])
            ->where('tab', 14)
            ->orderBy('order')
            ->get();
            
            // Группируем нормы по процессам и подпроцессам
            $groupedNorms = $norms->groupBy('process_name');
            
            // Расчет оценок
            $scores = $this->calculateScores($groupedNorms);
            
            return view('audits.conduct', [
                'organization' => $audit->organization,
                'audit' => $audit,
                'norms' => $norms,
                'process' => $process,
                'tabs' => $tabs,
                'tab' => $tab,
                'groupedNorms' => $groupedNorms,
                'scores' => $scores,
                'completedTabsInfo' => $completedTabsInfo,
                'lastVisitedTab' => $lastVisitedTab
            ]);
        }

        // Получаем нормы для текущей вкладки с их оценками
        $norms = Norm::with(['assessments' => function($query) use ($audit) {
            $query->where('audit_id', $audit->id);
        }])
        ->where('tab', $tab)
        ->orderBy('order')
        ->get();

        // Группируем нормы по процессам и подпроцессам
        $groupedNorms = $norms->groupBy('process_name');

        // Расчет оценок
        $scores = $this->calculateScores($groupedNorms);

        return view('audits.conduct', [
            'organization' => $audit->organization,
            'audit' => $audit,
            'norms' => $norms,
            'process' => $process,
            'tabs' => $tabs,
            'tab' => $tab,
            'groupedNorms' => $groupedNorms,
            'scores' => $scores,
            'completedTabsInfo' => $completedTabsInfo,
            'lastVisitedTab' => $lastVisitedTab
        ]);
    }

    private function getNormsForTab($tab, $audit)
    {
        // Для вкладок 1 и 2 берем реальные данные из БД
        if ($tab <= 2) {
            return Norm::with(['assessments' => function($query) use ($audit) {
                $query->where('audit_id', $audit->id);
            }])
            ->where('tab', $tab)
            ->orderBy('process_name')
            ->orderBy('subprocess_name')
            ->orderBy('order')
            ->get();
        }

        // Для вкладок 3-13 генерируем тестовые данные
        $norms = collect();
        
        // Структура данных для каждой вкладки
        $tabData = [
            3 => [
                'name' => 'Контроль целостности и защищенности информационной инфраструктуры',
                'subprocesses' => [
                    'Контроль уязвимостей',
                    'Управление обновлениями',
                    'Контроль целостности'
                ]
            ],
            4 => [
                'name' => 'Защита от вредоносного кода',
                'subprocesses' => [
                    'Антивирусная защита',
                    'Превентивная защита',
                    'Реагирование на инциденты'
                ]
            ],
            // ... добаьте структуру для остальных вкладок
        ];

        $processData = $tabData[$tab] ?? [
            'name' => "Процесс {$tab}",
            'subprocesses' => ["Подпроцесс {$tab}.1", "Подпроцесс {$tab}.2", "Подпроцесс {$tab}.3"]
        ];

        foreach ($processData['subprocesses'] as $subprocessIndex => $subprocess) {
            for ($i = 1; $i <= 5; $i++) {
                $normId = ($tab * 1000) + ($subprocessIndex * 100) + $i;
                
                $norm = new Norm([
                    'id' => $normId,
                    'tab' => $tab,
                    'process_name' => "Процесс {$tab}: " . $processData['name'],
                    'subprocess_name' => $subprocess,
                    'code' => "Норма {$tab}.{$subprocessIndex}.{$i}",
                    'description' => "Описание нормы {$tab}.{$subprocessIndex}.{$i}",
                    'implementation_type' => ['Т', 'О', 'Н'][rand(0, 2)],
                    'order' => $i
                ]);

                // Добавляем тестовую оценку
                $norm->assessments = collect([
                    new NormAssessment([
                        'audit_id' => $audit->id,
                        'norm_id' => $normId,
                        'score' => rand(0, 1)
                    ])
                ]);

                $norms->push($norm);
            }
        }

        return $norms;
    }

    private function calculateScoresForTab($groupedNorms, $audit)
    {
        $scores = [
            'subprocess' => [],
            'process' => []
        ];

        foreach ($groupedNorms as $processName => $subprocesses) {
            $processScores = [
                'tech' => [],
                'org' => []
            ];

            foreach ($subprocesses as $subprocessName => $norms) {
                $subprocessScores = [
                    'tech' => [],
                    'org' => []
                ];

                foreach ($norms as $norm) {
                    $assessment = $norm->assessments->first();
                    if ($assessment && $assessment->score !== null) {
                        if ($norm->implementation_type === 'Т') {
                            $subprocessScores['tech'][] = $assessment->score;
                            $processScores['tech'][] = $assessment->score;
                        } else {
                            $subprocessScores['org'][] = $assessment->score;
                            $processScores['org'][] = $assessment->score;
                        }
                    }
                }

                // Расчет оценки для подпроцесса
                $scores['subprocess'][$subprocessName] = [
                    'tech' => !empty($subprocessScores['tech']) ? min($subprocessScores['tech']) : 'н/о',
                    'org' => !empty($subprocessScores['org']) ? min($subprocessScores['org']) : 'н/о',
                    'total' => $this->calculateTotalScore($subprocessScores)
                ];
            }

            // Расчет оценки для процесса
            $scores['process'][$processName] = [
                'tech' => !empty($processScores['tech']) ? min($processScores['tech']) : 'н/о',
                'org' => !empty($processScores['org']) ? min($processScores['org']) : 'н/о',
                'total' => $this->calculateTotalScore($processScores)
            ];
        }

        return $scores;
    }

    private function calculateTotalScore($scores)
    {
        $techScore = !empty($scores['tech']) ? min($scores['tech']) : null;
        $orgScore = !empty($scores['org']) ? min($scores['org']) : null;

        if ($techScore === null && $orgScore === null) {
            return 'н/о';
        }

        if ($techScore === null) {
            return $orgScore;
        }

        if ($orgScore === null) {
            return $techScore;
        }

        return min($techScore, $orgScore);
    }

    private function getTestFields($tab)
    {
        $fields = [];
        
        // Генерируем 5 тестовых полей для каждого подпроцесса
        for ($i = 1; $i <= 5; $i++) {
            $fields[] = [
                'id' => "{$tab}_{$i}",
                'designation' => "Норма {$tab}.{$i}",
                'content' => "Описание нормы {$tab}.{$i}",
                'score' => null,
                'implementation' => '',
                'evidence' => ''
            ];
        }
        
        return $fields;
    }

    public function measure(Audit $audit, Norm $norm)
    {
        // Получаем существующую оценку и свидетельство, если есть
        $assessment = NormAssessment::where('audit_id', $audit->id)
            ->where('norm_id', $norm->id)
            ->first();

        return view('audits.measure', [
            'audit' => $audit,
            'norm' => $norm,
            'assessment' => $assessment,
            'organization' => $audit->organization
        ]);
    }

    public function showProcess($auditId, $processId)
    {
        $norms = [];
        for ($i = 1; $i <= 13; $i++) {
            $norms[$i] = NormAssessment::where('process_id', $i)->get();
        }

        // Логика для расчёта итоговх значений
        $summary = $this->calculateSummary($norms);

        return view('audit.process', compact('norms', 'summary', 'auditId', 'processId'));
    }

    public function saveAssessment(Request $request, $organization, Audit $audit)
    {
        try {
            \Log::info('Saving assessment', $request->all());

            // Получаем норму, чтобы определить вкладку
            $norm = Norm::findOrFail($request->norm_id);
            $tab = $norm->tab;

            // Определяем правило валидации в зависимости от вкладки
            $scoreValidation = 'nullable|numeric|in:0,1,-1';
            if (in_array($tab, [9, 10, 11, 12, 13])) { // B10, B11, B12, B13, ЖЦ AC
                $scoreValidation = 'nullable|numeric|in:0,0.5,1,-1';
            } else if ($tab == 14) { // НЗИ (Z)
                $scoreValidation = 'nullable|numeric|in:0,1';
            }

            $validated = $request->validate([
                'norm_id' => 'required|exists:norms,id',
                'score' => $scoreValidation,
                'process_name' => 'required|string'
            ]);

            // Сохраняем score как есть, null - это невыставленная оценка,
            // -1 - это оценка "н/о", 0.5 - это средняя оценка (только для вкладок B10-B13 и ЖЦ АС)
            // При этом при JSON конвертации null остается null

            // Сначала найдем существующую запись
            $assessment = NormAssessment::where([
                'audit_id' => $audit->id,
                'norm_id' => $validated['norm_id']
            ])->first();

            if ($assessment) {
                // Если запись существует - обновляем
                $assessment->score = $validated['score'];
                $assessment->save();
            } else {
                // Если записи нет - создаем новую
                $assessment = NormAssessment::create([
                    'audit_id' => $audit->id,
                    'norm_id' => $validated['norm_id'],
                    'score' => $validated['score']
                ]);
            }

            \Log::info('Assessment saved', ['assessment' => $assessment]);

            // Получаем норму для определения процесса
            $norm = Norm::find($validated['norm_id']);
            
            // Пересчитываем общую оценку процесса
            $processScore = $this->calculateProcessScore($audit->id, $norm->process_name);

            return response()->json([
                'success' => true,
                'data' => [
                    'assessment' => $assessment,
                    'process_score' => $processScore
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saving assessment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при сохранении оценки: ' . $e->getMessage()
            ], 500);
        }
    }

    private function calculateSummary($norms)
    {
        // Пример логики для расчёта итоговых значений
        $summary = [];
        foreach ($norms as $processNorms) {
            foreach ($processNorms as $norm) {
                // Добавьте логику для расчёта итогов
            }
        }
        return $summary;
    }

    public function recount($id)
    {
        $audit = Audit::findOrFail($id);
        
        // Получаем все процессы
        $processes = [
            1 => 'Процесс 1 "Обеспечение защиты информации при управлении доступом"',
            2 => 'Процесс 2 "Обеспечение защиты вычислительных сетей"',
            3 => 'Процесс 3 "Контроль целостности и защищенности информационной инфраструктуры"',
            4 => 'Процесс 4 "Защита от вредоносного кода"',
            5 => 'Процесс 5 "Предотвращение утечек информации"',
            6 => 'Процесс 6 "Управление инцидентами защиты информации"',
            7 => 'Процесс 7 "Защита среды виртуализации"',
            8 => 'Процесс 8 "Защита информации при осуществлении удаленного логического доступа"'
        ];

        foreach ($processes as $number => $name) {
            // Здесь будет логика расчета оценок для каждого процесса
            $evaluation = ProcessEvaluation::updateOrCreate(
                [
                    'audit_id' => $id,
                    'process_number' => $number
                ],
                [
                    'process_name' => $name,
                    // Расчет оценок будет происходить здесь
                ]
            );
        }

        return redirect()->back()->with('success', 'Оценки обновлены');
    }

    public function stop(Audit $audit)
    {
        // Логика завершения аудита
        $audit->status = 'completed';
        $audit->end_date = now();
        $audit->save();
        
        return redirect()->route('organizations.audits.index', $audit->organization)
            ->with('success', 'Аудит завершен');
    }

    private function calculateScores($groupedNorms)
    {
        $scores = [
            'subprocess' => [],
            'process' => []
        ];

        foreach ($groupedNorms as $processName => $norms) {
            $processScores = [
                'tech' => [],
                'org' => []
            ];

            // Группируем нормы по подпроцессам
            $subprocessGroups = $norms->groupBy('subprocess_name');

            foreach ($subprocessGroups as $subprocessName => $subprocessNorms) {
                $subprocessScores = [
                    'tech' => [],
                    'org' => []
                ];
                
                foreach ($subprocessNorms as $norm) {
                    $assessment = $norm->assessments->first();
                    // Исключаем оценки "н/о" (значение -1 или 'н/о')
                    if ($assessment && $assessment->score !== null && $assessment->score !== 'н/о' && $assessment->score !== -1) {
                        if ($norm->implementation_type === 'Т') {
                            $subprocessScores['tech'][] = $assessment->score;
                            $processScores['tech'][] = $assessment->score;
                        } else {
                            $subprocessScores['org'][] = $assessment->score;
                            $processScores['org'][] = $assessment->score;
                        }
                    }
                }
                
                // Расчет оценки для подпроцесса
                $scores['subprocess'][$subprocessName] = [
                    'tech' => !empty($subprocessScores['tech']) ? min($subprocessScores['tech']) : 'н/о',
                    'org' => !empty($subprocessScores['org']) ? min($subprocessScores['org']) : 'н/о',
                    'total' => $this->calculateTotalScore($subprocessScores)
                ];
            }

            // Расчет оценки для процесса
            $scores['process'][$processName] = [
                'tech' => !empty($processScores['tech']) ? min($processScores['tech']) : 'н/о',
                'org' => !empty($processScores['org']) ? min($processScores['org']) : 'н/о',
                'total' => $this->calculateTotalScore($processScores)
            ];
        }
        
        return $scores;
    }

    public function updateAssessment(Request $request, Organization $organization, Audit $audit)
    {
        $validated = $request->validate([
            'norm_id' => 'required|exists:norms,id',
            'evidence' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $assessment = NormAssessment::updateOrCreate(
            [
                'norm_id' => $validated['norm_id'],
                'audit_id' => $audit->id
            ],
            [
                'evidence' => $validated['evidence'],
                'notes' => $validated['notes']
            ]
        );

        return redirect()->back()->with('success', 'Свидетельство сохранено');
    }

    public function itogo(Audit $audit, $process = null, $tab = null)
    {
        // Получаем вкладки
        $tabs = $this->getTabs();
        
        // Получаем все процессы для данного аудита
        $processes = Process::where('audit_id', $audit->id)->get();
        
        // Если процессов нет, создаем их
        if ($processes->isEmpty()) {
            $defaultProcesses = [
                1 => 'Процесс 1 "Обеспечение защиты информации при управлении доступом"',
                2 => 'Процесс 2 "Обеспечение защиты вычислительных сетей"',
                3 => 'Процесс 3 "Контроль целостности и защищенности информационной инфраструктуры"',
                4 => 'Процесс 4 "Защита от вредоносного кода"',
                5 => 'Процесс 5 "Предотвращение утечек информации"',
                6 => 'Процесс 6 "Управление инцидентами защиты информации"',
                7 => 'Процесс 7 "Защита среды виртуализации"',
                8 => 'Процесс 8 "Защита информации при осуществлении удаленного логического доступа"'
            ];

            foreach ($defaultProcesses as $number => $name) {
                Process::create([
                    'audit_id' => $audit->id,
                    'number' => $number,
                    'technical_score' => 'н/о',
                    'planning_score' => 'н/о',
                    'implementation_score' => 'н/о',
                    'control_score' => 'н/о',
                    'improvement_score' => 'н/о',
                    'qualitative_score' => 'н/о',
                    'numerical_score' => 'н/о',
                    'violations_count' => 0
                ]);
            }
            
            $processes = Process::where('audit_id', $audit->id)->get();
        }

        // Формируем массив оценок
        $scores = [];
        foreach ($processes as $proc) {
            $processName = "Процесс {$proc->number}";
            $scores[$processName] = [
                'technical_score' => $proc->technical_score,
                'planning_score' => $proc->planning_score,
                'implementation_score' => $proc->implementation_score,
                'control_score' => $proc->control_score,
                'improvement_score' => $proc->improvement_score,
                'qualitative_score' => $proc->qualitative_score,
                'numerical_score' => $proc->numerical_score,
            ];
        }

        // Расчет итоговых показателей
        $lifecycle_score = $processes->avg('numerical_score') ?? 'н/о';
        $violations_count = $processes->sum('violations_count') ?? 0;
        $final_score = $this->calculateFinalScore($lifecycle_score, $violations_count);

        return view('audits.itogo', compact(
            'audit',
            'process',
            'tab',
            'scores',
            'lifecycle_score',
            'violations_count',
            'final_score',
            'tabs'
        ));
    }

    public function printScores(Audit $audit, $process, $tab)
    {
        $norms = Norm::with(['assessments' => function($query) use ($audit) {
            $query->where('audit_id', $audit->id);
        }])
        ->where('tab', $tab)
        ->orderBy('process_name')
        ->orderBy('subprocess_name')
        ->orderBy('order')
        ->get();

        $groupedNorms = $norms->groupBy(['process_name', 'subprocess_name']);
        $processName = $norms->first()?->process_name ?? '';

        // Рассчитываем оценки
        $scores = [];
        foreach ($groupedNorms as $processName => $subprocesses) {
            foreach ($subprocesses as $subprocessName => $norms) {
                $subprocessScores = [];
                foreach ($norms as $norm) {
                    $assessment = $norm->assessments->first();
                    if ($assessment && $assessment->score !== null) {
                        $subprocessScores[] = $assessment->score;
                    }
                }
                $scores[$subprocessName] = !empty($subprocessScores) ? min($subprocessScores) : 'н/о';
            }
        }

        return view('audits.print.scores', compact('groupedNorms', 'scores', 'processName'));
    }

    /**
     * Отображает страницу с таблицей свидетельств для печати
     */
    public function printEvidence(Audit $audit, $process, $tab)
    {
        try {
            // Получаем все нормы с документами и свидетельствами для всех процессов
            $allNorms = Norm::with(['evidences' => function($query) use ($audit) {
                $query->where('audit_id', $audit->id)
                      ->with('user');
            }])
            ->where('tab', $tab)
            ->orderBy('process_name')
            ->orderBy('code')
            ->get();

            // Группируем нормы по процессам
            $processList = $allNorms->groupBy('process_name');
            
            // Фильтруем нормы, у которых есть свидетельства
            $processesWithEvidences = [];
            foreach ($processList as $processName => $norms) {
                $normsWithEvidences = $norms->filter(function($norm) {
                    return $norm->evidences && $norm->evidences->count() > 0;
                });
                
                if ($normsWithEvidences->isNotEmpty()) {
                    $processesWithEvidences[$processName] = $normsWithEvidences;
                }
            }

            return view('audits.print.evidence', [
                'audit' => $audit,
                'process' => $process,
                'tab' => $tab,
                'processesWithEvidences' => $processesWithEvidences
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in printEvidence: ' . $e->getMessage());
            return back()->with('error', 'Ошибка при формировании списка свидетельств для печати');
        }
    }

    public function tab14(Audit $audit)
    {
        // Получаем оценки из базы данных или сессии
        $scores = $this->getScores($audit->id); // Метод для получения оценок

        // Получаем нормы для вкладки 14
        $norms = Norm::where('tab', 14)->orderBy('order')->get();

        return view('audits.tab14', compact('audit', 'scores', 'norms'));
    }

    public function loadProcessScores(Request $request)
    {
        try {
            $audit_id = $request->query('audit_id');
            
            // Получаем все оценки процессов
            $processScores = ProcessScore::where('audit_id', $audit_id)->get();
            
            \Log::info('Loading process scores', [
                'audit_id' => $audit_id,
                'scores' => $processScores->toArray()
            ]);

            // Получаем оценки норм для подсчета нарушений
            $violations = NormAssessment::where('audit_id', $audit_id)
                ->where('score', 0)
                ->count();
                
            // Рассчитываем общую оценку жизненного цикла
            $lifecycle_score = $this->calculateLifecycleScore($processScores);
            
            // Рассчитываем итоговую оценку
            $final_score = $this->calculateFinalScore($lifecycle_score, $violations);

            return response()->json([
                'success' => true,
                'data' => [
                    'process_scores' => $processScores,
                    'lifecycle_score' => $lifecycle_score,
                    'violations_count' => $violations,
                    'final_score' => $final_score
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading process scores: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при загрузке оценок: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveProcessScores(Request $request)
    {
        try {
            \Log::info('Saving process scores', $request->all());

            $validated = $request->validate([
                'audit_id' => 'required|exists:audits,id',
                'process_name' => 'required|string',
                'norm_id' => 'required|integer',
                'score' => 'required|string'
            ]);

            // Сохраняем оценку нормы
            $normAssessment = NormAssessment::updateOrCreate(
                [
                    'audit_id' => $validated['audit_id'],
                    'norm_id' => $validated['norm_id']
                ],
                [
                    'score' => $validated['score']
                ]
            );

            \Log::info('Norm assessment saved', ['assessment' => $normAssessment]);

            // Пересчитываем общую оценку процесса
            $processScore = $this->calculateProcessScore(
                $validated['audit_id'],
                $validated['process_name']
            );

            \Log::info('Process score calculated', ['score' => $processScore]);

            return response()->json([
                'success' => true,
                'data' => [
                    'norm_assessment' => $normAssessment,
                    'process_score' => $processScore
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saving process scores: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при сохранении оценки: ' . $e->getMessage()
            ], 500);
        }
    }

    private function calculateProcessScore($auditId, $processName)
    {
        try {
            \Log::info('Calculating process score', [
                'audit_id' => $auditId,
                'process_name' => $processName
            ]);

            // Получаем все оценки норм для данного процесса
            $normScores = NormAssessment::join('norms', 'norm_assessments.norm_id', '=', 'norms.id')
                ->where('norm_assessments.audit_id', $auditId)
                ->where('norms.process_name', $processName)
                ->get();

            \Log::info('Found norm scores', ['scores' => $normScores->toArray()]);

            // Считаем средние оценки по каждому аспекту
            $technicalScores = $normScores->where('implementation_type', 'technical')->pluck('score');
            $planningScores = $normScores->where('implementation_type', 'planning')->pluck('score');
            $implementationScores = $normScores->where('implementation_type', 'implementation')->pluck('score');
            $controlScores = $normScores->where('implementation_type', 'control')->pluck('score');
            $improvementScores = $normScores->where('implementation_type', 'improvement')->pluck('score');

            // Рассчитываем оценки
            $scores = [
                'technical_score' => $this->calculateAverageScore($technicalScores),
                'planning_score' => $this->calculateAverageScore($planningScores),
                'implementation_score' => $this->calculateAverageScore($implementationScores),
                'control_score' => $this->calculateAverageScore($controlScores),
                'improvement_score' => $this->calculateAverageScore($improvementScores)
            ];

            \Log::info('Calculated aspect scores', ['scores' => $scores]);

            // Рассчитываем качественную и числовую оценки
            $allScores = collect($scores)->filter(function($score) {
                return $score !== 'н/о' && is_numeric($score);
            })->values();

            $scores['qualitative_score'] = $this->calculateQualitativeScore($allScores);
            $scores['numerical_score'] = $this->calculateNumericalScore($allScores);

            \Log::info('Final process scores', ['scores' => $scores]);

            // Сохраняем оценки процесса
            $processScore = ProcessScore::updateOrCreate(
                [
                    'audit_id' => $auditId,
                    'process_name' => $processName
                ],
                $scores
            );

            \Log::info('Saved process score', ['process_score' => $processScore->toArray()]);

            return $processScore;
        } catch (\Exception $e) {
            \Log::error('Error calculating process score: ' . $e->getMessage());
            throw $e;
        }
    }

    private function calculateQualitativeScore($scores)
    {
        if ($scores->isEmpty()) {
            return 'н/о';
        }

        $avg = $scores->avg();
        
        if ($avg >= 2.5) return 'Высокий';
        if ($avg >= 1.5) return 'Средний';
        return 'Низкий';
    }

    private function calculateNumericalScore($scores)
    {
        if ($scores->isEmpty()) {
            return 'н/о';
        }

        return number_format($scores->avg(), 2);
    }

    private function calculateAverageScore($scores)
    {
        if ($scores->isEmpty()) {
            return -1; // Возвращаем -1 вместо 'н/о'
        }

        // Если все оценки -1 (н/о), возвращаем -1
        if ($scores->every(function ($score) {
            return $score === -1 || $score === 'н/о' || $score === null;
        })) {
            return -1;
        }

        // Фильтруем только числовые оценки, исключая -1 (н/о) и 'н/о'
        $numericScores = $scores->filter(function ($score) {
            return is_numeric($score) && $score !== -1 && $score !== 'н/о';
        });

        if ($numericScores->isEmpty()) {
            return -1;
        }

        return round($numericScores->avg(), 2);
    }

    private function calculateLifecycleScore($processScores)
    {
        // Логика расчета общей оценки по жизненному циклу
        $scores = $processScores->pluck('numerical_score')->filter()->map(function($score) {
            return is_numeric($score) ? $score : 0;
        });
        
        return $scores->count() > 0 ? $scores->avg() : 'н/о';
    }

    private function calculateViolationsCount($auditId)
    {
        // Логика подсчета нарушений
        return NormAssessment::where('audit_id', $auditId)
            ->where('score', 0)
            ->count();
    }

    private function calculateFinalScore($lifecycle_score, $violations_count)
    {
        // Логика расчета итоговой оценки
        if ($lifecycle_score === 'н/о') return 'н/о';
        
        $score = floatval($lifecycle_score);
        if ($violations_count > 0) {
            $score = max(0, $score - ($violations_count * 0.1)); // Уменьшаем оценку за каждое нарушение
        }
        
        return number_format($score, 2);
    }

    public function showEvidence(Organization $organization, Audit $audit, Norm $norm = null, $process = null, $tab = null)
    {
        $query = $audit->evidences()->with(['user', 'norm']);
        
        if ($norm) {
            $query->where('norm_id', $norm->id);
        }
        
        if ($process && $tab) {
            $query->whereHas('norm', function($q) use ($process, $tab) {
                $q->where('tab', $tab)
                  ->where('process_name', 'like', "Процесс {$process}%");
            });
        }
        
        $evidences = $query->orderBy('created_at', 'desc')->get();
        
        return view('audits.evidence', compact('organization', 'audit', 'evidences', 'norm', 'process', 'tab'));
    }

    public function storeEvidence(Request $request, Organization $organization, Audit $audit)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'description' => 'required|string|max:255',
            'norm_id' => 'required|exists:norms,id'
        ]);

        try {
            // Убедимся, что директория существует
            $evidencesPath = storage_path('app/public/evidences');
            if (!file_exists($evidencesPath)) {
                if (!mkdir($evidencesPath, 0775, true)) {
                    throw new \Exception('Не удалось создать директорию для файлов');
                }
            }

            // Генерируем уникальное имя файла
            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            
            // Сохраняем файл
            if (!$request->file('file')->move($evidencesPath, $fileName)) {
                throw new \Exception('Не удалось сохранить файл');
            }

            // Сохраняем запись в БД
            $evidence = $audit->evidences()->create([
                'norm_id' => $request->norm_id,
                'file_path' => 'evidences/' . $fileName, // путь относительно public/storage
                'description' => $request->description,
                'user_id' => auth()->id()
            ]);

            return back()->with('success', 'Свидетельство успешно добавлено');
        } catch (\Exception $e) {
            \Log::error('Error storing evidence: ' . $e->getMessage());
            return back()->with('error', 'Ошибка при сохранении файла: ' . $e->getMessage());
        }
    }

    private function getTabs()
    {
        return [
            1 => 'B1',
            2 => 'B2',
            3 => 'B3',
            4 => 'B4',
            5 => 'B5',
            6 => 'B6',
            7 => 'B7',
            8 => 'B8',
            9 => 'B10',
            10 => 'B11',
            11 => 'B12',
            12 => 'B13',
            13 => 'ЖЦ AC',
            14 => 'НЗИ (Z)',
            15 => 'B14 Оценки'
        ];
    }

    private function getQualitativeScore($score)
    {
        if ($score >= 2.5) return 'Высокий';
        if ($score >= 1.5) return 'Средний';
        return 'Низкий';
    }

    /**
     * Отображает страницу с документами аудита
     */
    public function documents(Audit $audit, $process, $tab)
    {
        // Получаем все документы для данного аудита
        $documents = NormDocument::where('audit_id', $audit->id)
                                ->whereHas('norm', function($query) use ($tab) {
                                    $query->where('tab', $tab);
                                })
                                ->get();
        
        // Получаем нормы для текущей вкладки
        $norms = Norm::where('tab', $tab)->orderBy('order')->get();
        
        return view('audits.documents', compact('audit', 'documents', 'norms', 'process', 'tab'));
    }

    /**
     * Генерирует полный PDF-отчет по аудиту
     */
    public function printFullReport(Audit $audit)
    {
        try {
            // Получаем данные для отчета
            $organization = $audit->organization;
            
            // Используем DB фасад вместо модели Process
            $processes = DB::table('processes')->where('audit_id', $audit->id)->get();
            
            // Получаем все вкладки
            $tabs = $this->getTabs();
            
            // Получаем данные для страницы итогов
            $scores = [];
            foreach ($processes as $proc) {
                $processName = "Процесс {$proc->number}";
                $scores[$processName] = [
                    'technical_score' => $proc->technical_score,
                    'planning_score' => $proc->planning_score,
                    'implementation_score' => $proc->implementation_score,
                    'control_score' => $proc->control_score,
                    'improvement_score' => $proc->improvement_score,
                    'qualitative_score' => $proc->qualitative_score,
                    'numerical_score' => $proc->numerical_score,
                ];
            }
            
            $lifecycle_score = $processes->avg('numerical_score') ?? 'н/о';
            $violations_count = $processes->sum('violations_count') ?? 0;
            $final_score = $this->calculateFinalScore($lifecycle_score, $violations_count);
            
            // Собираем данные для всех печатных страниц
            $printData = [];
            
            // Данные для страницы итогов
            $printData['itogo'] = [
                'scores' => $scores,
                'lifecycle_score' => $lifecycle_score,
                'violations_count' => $violations_count,
                'final_score' => $final_score
            ];
            
            // Данные для печати списков норм по вкладкам
            $printData['lists'] = [];
            
            // Сначала добавляем данные для всех вкладок без привязки к процессам
            foreach ($tabs as $tabId => $tabName) {
                // Получаем все нормы для данной вкладки
                $norms = Norm::with(['assessments' => function($query) use ($audit) {
                    $query->where('audit_id', $audit->id);
                }])
                ->where('tab', $tabId)
                ->orderBy('process_name')
                ->orderBy('subprocess_name')
                ->orderBy('order')
                ->get();
                
                // Группируем нормы по процессам
                $normsByProcess = $norms->groupBy('process_name');
                
                foreach ($normsByProcess as $processName => $processNorms) {
                    if ($processNorms->isNotEmpty()) {
                        // Определяем номер процесса из имени (если есть)
                        preg_match('/Процесс\s*(\d+)/i', $processName, $matches);
                        $processNumber = isset($matches[1]) ? $matches[1] : 0;
                        
                        $printData['lists'][] = [
                            'processName' => $processName,
                            'norms' => $processNorms,
                            'process' => $processNumber,
                            'tab' => $tabId
                        ];
                    }
                }
            }
            
            // Данные для печати свидетельств
            $printData['evidences'] = [];
            
            // Сначала добавляем свидетельства по всем вкладкам без привязки к процессам
            foreach ($tabs as $tabId => $tabName) {
                $allNorms = Norm::with(['evidences' => function($query) use ($audit) {
                    $query->where('audit_id', $audit->id)
                          ->with('user');
                }])
                ->where('tab', $tabId)
                ->orderBy('process_name')
                ->orderBy('code')
                ->get();
                
                $processList = $allNorms->groupBy('process_name');
                $processesWithEvidences = [];
                
                foreach ($processList as $processName => $norms) {
                    $normsWithEvidences = $norms->filter(function($norm) {
                        return $norm->evidences && $norm->evidences->count() > 0;
                    });
                    
                    if ($normsWithEvidences->isNotEmpty()) {
                        $processesWithEvidences[$processName] = $normsWithEvidences;
                    }
                }
                
                if (!empty($processesWithEvidences)) {
                    // Определяем номер процесса из имени (если есть)
                    foreach (array_keys($processesWithEvidences) as $processName) {
                        preg_match('/Процесс\s*(\d+)/i', $processName, $matches);
                        $processNumber = isset($matches[1]) ? $matches[1] : 0;
                        
                        $printData['evidences'][] = [
                            'processesWithEvidences' => [$processName => $processesWithEvidences[$processName]],
                            'process' => $processNumber,
                            'tab' => $tabId
                        ];
                    }
                }
            }
            
            // Генерируем PDF
            $pdf = PDF::loadView('audits.print.full_report', [
                'audit' => $audit,
                'organization' => $organization,
                'printData' => $printData,
                'tabs' => $tabs
            ]);
            
            // Увеличиваем лимит памяти для большого отчета
            $pdf->setOption('memory_limit', '512M'); // Увеличиваем лимит памяти
            $pdf->setOption('max_execution_time', 600); // Увеличиваем время выполнения
            
            return $pdf->download("audit_{$audit->id}_full_report.pdf");
        } catch (\Exception $e) {
            \Log::error('Error generating full report: ' . $e->getMessage());
            return back()->with('error', 'Ошибка при формировании полного отчета: ' . $e->getMessage());
        }
    }

    /**
     * Генерирует Word-отчет по аудиту
     */
    public function downloadWordReport(Audit $audit)
    {
        try {
            // Путь к шаблону Word
            $templatePath = public_path('templates/audit_report_template.docx');
            
            // Проверяем существование шаблона
            if (!file_exists($templatePath)) {
                // Если шаблона нет, создаем пустой документ
                $phpWord = new \PhpOffice\PhpWord\PhpWord();
                $section = $phpWord->addSection();
                
                // Добавляем заголовок
                $section->addText('Отчет по аудиту #' . $audit->id, ['bold' => true, 'size' => 16], ['alignment' => 'center']);
                $section->addText($audit->organization->name, ['bold' => true, 'size' => 14], ['alignment' => 'center']);
                $section->addText('Дата: ' . now()->format('d.m.Y'), ['size' => 12], ['alignment' => 'right']);
                
                // Добавляем информацию об аудите
                $section->addTextBreak(2);
                $section->addText('Информация об аудите:', ['bold' => true, 'size' => 14]);
                $section->addText('Название: ' . $audit->title);
                $section->addText('Описание: ' . $audit->description);
                $section->addText('Дата начала: ' . $audit->start_date->format('d.m.Y'));
                $section->addText('Дата окончания: ' . ($audit->end_date ? $audit->end_date->format('d.m.Y') : 'Не завершен'));
                
                // Добавляем таблицу с результатами
                $section->addTextBreak(2);
                $section->addText('Результаты аудита:', ['bold' => true, 'size' => 14]);
                
                $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
                
                // Заголовок таблицы
                $table->addRow();
                $table->addCell(2000, ['bgColor' => 'EEEEEE'])->addText('Процесс', ['bold' => true]);
                $table->addCell(1500, ['bgColor' => 'EEEEEE'])->addText('Тех. защита', ['bold' => true]);
                $table->addCell(1500, ['bgColor' => 'EEEEEE'])->addText('Планирование', ['bold' => true]);
                $table->addCell(1500, ['bgColor' => 'EEEEEE'])->addText('Реализация', ['bold' => true]);
                $table->addCell(1500, ['bgColor' => 'EEEEEE'])->addText('Контроль', ['bold' => true]);
                $table->addCell(1500, ['bgColor' => 'EEEEEE'])->addText('Совершенствование', ['bold' => true]);
                $table->addCell(1500, ['bgColor' => 'EEEEEE'])->addText('Итоговая оценка', ['bold' => true]);
                
                // Данные таблицы
                $processes = DB::table('processes')->where('audit_id', $audit->id)->get();
                foreach ($processes as $proc) {
                    $table->addRow();
                    $table->addCell(2000)->addText("Процесс {$proc->number}");
                    $table->addCell(1500)->addText($proc->technical_score);
                    $table->addCell(1500)->addText($proc->planning_score);
                    $table->addCell(1500)->addText($proc->implementation_score);
                    $table->addCell(1500)->addText($proc->control_score);
                    $table->addCell(1500)->addText($proc->improvement_score);
                    $table->addCell(1500)->addText($proc->numerical_score);
                }
                
                // Итоговые данные
                $lifecycle_score = $processes->avg('numerical_score') ?? 'н/о';
                $violations_count = $processes->sum('violations_count') ?? 0;
                $final_score = $this->calculateFinalScore($lifecycle_score, $violations_count);
                
                $table->addRow();
                $cell = $table->addCell(9000, ['gridSpan' => 6]);
                $cell->addText('Итоговая оценка по жизненному циклу:', ['bold' => true], ['alignment' => 'right']);
                $table->addCell(1500)->addText($lifecycle_score);
                
                $table->addRow();
                $cell = $table->addCell(9000, ['gridSpan' => 6]);
                $cell->addText('Количество нарушений:', ['bold' => true], ['alignment' => 'right']);
                $table->addCell(1500)->addText($violations_count);
                
                $table->addRow();
                $cell = $table->addCell(9000, ['gridSpan' => 6]);
                $cell->addText('Итоговая оценка:', ['bold' => true], ['alignment' => 'right']);
                $table->addCell(1500)->addText($final_score);
            } else {
                // Если шаблон существует, используем его
                $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
                
                // Заполняем данные в шаблоне
                $templateProcessor->setValue('audit_id', $audit->id);
                $templateProcessor->setValue('organization_name', $audit->organization->name);
                $templateProcessor->setValue('audit_title', $audit->title);
                $templateProcessor->setValue('audit_description', $audit->description);
                $templateProcessor->setValue('start_date', $audit->start_date->format('d.m.Y'));
                $templateProcessor->setValue('end_date', $audit->end_date ? $audit->end_date->format('d.m.Y') : 'Не завершен');
                $templateProcessor->setValue('current_date', now()->format('d.m.Y'));
                
                // Заполняем таблицу с процессами
                $processes = DB::table('processes')->where('audit_id', $audit->id)->get();
                $templateProcessor->cloneRow('process_number', $processes->count());
                
                $i = 1;
                foreach ($processes as $proc) {
                    $templateProcessor->setValue('process_number#' . $i, $proc->number);
                    $templateProcessor->setValue('technical_score#' . $i, $proc->technical_score);
                    $templateProcessor->setValue('planning_score#' . $i, $proc->planning_score);
                    $templateProcessor->setValue('implementation_score#' . $i, $proc->implementation_score);
                    $templateProcessor->setValue('control_score#' . $i, $proc->control_score);
                    $templateProcessor->setValue('improvement_score#' . $i, $proc->improvement_score);
                    $templateProcessor->setValue('numerical_score#' . $i, $proc->numerical_score);
                    $i++;
                }
                
                // Итоговые данные
                $lifecycle_score = $processes->avg('numerical_score') ?? 'н/о';
                $violations_count = $processes->sum('violations_count') ?? 0;
                $final_score = $this->calculateFinalScore($lifecycle_score, $violations_count);
                
                $templateProcessor->setValue('lifecycle_score', $lifecycle_score);
                $templateProcessor->setValue('violations_count', $violations_count);
                $templateProcessor->setValue('final_score', $final_score);
                
                // Сохраняем временный файл
                $tempFile = tempnam(sys_get_temp_dir(), 'audit_report_');
                $templateProcessor->saveAs($tempFile);
                
                // Возвращаем файл для скачивания
                return response()->download($tempFile, "audit_{$audit->id}_report.docx")->deleteFileAfterSend(true);
            }
            
            // Сохраняем документ во временный файл
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $tempFile = tempnam(sys_get_temp_dir(), 'audit_report_');
            $objWriter->save($tempFile);
            
            // Возвращаем файл для скачивания
            return response()->download($tempFile, "audit_{$audit->id}_report.docx")->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            \Log::error('Error generating Word report: ' . $e->getMessage());
            return back()->with('error', 'Ошибка при формировании Word-отчета: ' . $e->getMessage());
        }
    }

    /**
     * Скачивает случайный Word-документ
     */
    public function downloadRandomWordReport(Audit $audit)
    {
        // Путь к случайному Word-файлу
        $filePath = public_path('templates/random_report.docx');
        
        // Если файла нет, создаем пустой документ
        if (!file_exists($filePath)) {
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();
            
            // Добавляем случайный текст
            $section->addText('Отчет по аудиту #' . $audit->id, ['bold' => true, 'size' => 16], ['alignment' => 'center']);
            $section->addText('Случайный отчет', ['bold' => true, 'size' => 14], ['alignment' => 'center']);
            $section->addText('Дата: ' . now()->format('d.m.Y'), ['size' => 12], ['alignment' => 'right']);
            
            // Добавляем случайный текст
            $section->addTextBreak(2);
            $section->addText('Это случайный отчет, сгенерированный системой.', ['size' => 12]);
            $section->addText('Организация: ' . $audit->organization->name);
            $section->addText('Аудит: ' . $audit->title);
            
            // Сохраняем документ во временный файл
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $filePath = tempnam(sys_get_temp_dir(), 'random_report_');
            $objWriter->save($filePath);
        }
        
        // Возвращаем файл для скачивания
        return response()->download($filePath, "audit_{$audit->id}_report.docx")->deleteFileAfterSend(true);
    }

    public function saveScore(Request $request)
    {
        try {
            $validated = $request->validate([
                'normId' => 'required|integer',
                'auditId' => 'required|integer',
                'score' => 'nullable'
            ]);

            // Получаем норму, чтобы определить вкладку
            $norm = Norm::findOrFail($validated['normId']);
            $tab = $norm->tab;
            $allowHalfScore = in_array($tab, [9, 10, 11, 12, 13]); // B10, B11, B12, B13, ЖЦ AC
            $isNziTab = $tab == 14; // НЗИ (Z)

            // Преобразуем строку 'н/о' в -1
            // Преобразуем строку '0.5' в 0.5, только если это разрешенная вкладка
            // null остается null (невыставленная оценка)
            $score = $validated['score'];
            if ($score === 'н/о') {
                // Проверка, что "н/о" не для вкладки НЗИ (Z)
                if ($isNziTab) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Для вкладки НЗИ (Z) доступны только оценки 0 и 1'
                    ], 400);
                }
                $score = -1;
            } else if ($score === '0.5' && $allowHalfScore) {
                $score = 0.5;
            } else if ($score === '0.5' && !$allowHalfScore) {
                // Если оценка 0.5 не разрешена для этой вкладки, округляем до 0
                $score = 0;
            }

            $assessment = NormAssessment::updateOrCreate(
                [
                    'norm_id' => $validated['normId'],
                    'audit_id' => $validated['auditId']
                ],
                [
                    'score' => $score
                ]
            );

            // Обновляем оценки процесса
            $this->updateProcessScores($validated['auditId']);

            return response()->json([
                'success' => true,
                'message' => 'Оценка сохранена',
                'data' => $assessment
            ]);

        } catch (\Exception $e) {
            \Log::error('Error saving score: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при сохранении оценки'
            ], 500);
        }
    }

    private function updateProcessScores($auditId)
    {
        $processes = Process::where('audit_id', $auditId)->get();
        
        foreach ($processes as $process) {
            $assessments = NormAssessment::where('audit_id', $auditId)
                ->whereHas('norm', function($query) use ($process) {
                    $query->where('process_name', $process->name);
                })
                ->get();

            // Получаем оценки по типам
            $scores = [
                'technical_score' => $this->calculateAverageScore($assessments->where('norm.score_type', 'technical')->pluck('score')),
                'planning_score' => $this->calculateAverageScore($assessments->where('norm.score_type', 'planning')->pluck('score')),
                'implementation_score' => $this->calculateAverageScore($assessments->where('norm.score_type', 'implementation')->pluck('score')),
                'control_score' => $this->calculateAverageScore($assessments->where('norm.score_type', 'control')->pluck('score')),
                'improvement_score' => $this->calculateAverageScore($assessments->where('norm.score_type', 'improvement')->pluck('score'))
            ];

            // Преобразуем -1 в 'н/о' перед сохранением в процесс
            foreach ($scores as $key => $value) {
                $scores[$key] = $value === -1 ? 'н/о' : $value;
            }

            $process->update($scores);
        }
    }

    /**
     * Скачать архив всех документов аудита
     * 
     * @param Audit $audit
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadDocumentsArchive(Audit $audit)
    {
        try {
            \Log::info('Начало скачивания архива для аудита ID: ' . $audit->id);
            \Log::info('Рабочая директория: ' . getcwd());
            
            // Получаем все документы, относящиеся к этому аудиту
            $documents = NormDocument::where('audit_id', $audit->id)->get();
            \Log::info('Найдено документов: ' . $documents->count());
            
            if ($documents->isEmpty()) {
                \Log::warning('Документы для аудита не найдены, ID: ' . $audit->id);
                return back()->with('error', 'Документы для этого аудита не найдены');
            }
            
            // Создаем временную директорию для файлов
            $tempDir = storage_path('app/temp/archives/' . uniqid());
            \Log::info('Создана временная директория: ' . $tempDir);
            
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
                \Log::info('Директория создана');
            }
            
            // Формируем структуру архива по вкладкам и процессам
            $tabs = $this->getTabs();
            
            // Создаем подпапки для каждой вкладки
            foreach ($tabs as $tabId => $tabName) {
                $tabDir = $tempDir . '/' . $tabId . ' - ' . $tabName;
                mkdir($tabDir, 0755, true);
                \Log::info('Создана директория для вкладки: ' . $tabDir);
            }
            
            // Копируем файлы в соответствующие папки
            $copiedFilesCount = 0;
            $errorCopyCount = 0;
            
            foreach ($documents as $document) {
                try {
                    \Log::info('Обработка документа ID: ' . $document->id . ', путь: ' . $document->file_path);
                    
                    // Получаем норму, связанную с документом
                    $norm = $document->norm;
                    
                    if (!$norm) {
                        // Если норма не найдена, помещаем в папку "Другие"
                        $processDir = $tempDir . '/Другие';
                        if (!file_exists($processDir)) {
                            mkdir($processDir, 0755, true);
                            \Log::info('Создана директория "Другие": ' . $processDir);
                        }
                        \Log::info('Документ ID: ' . $document->id . ' помещен в папку "Другие" (Норма не найдена)');
                    } else {
                        // Определяем вкладку и процесс для этой нормы
                        $tabId = $norm->tab_id;
                        $tabName = $tabs[$tabId] ?? 'Другие';
                        $processName = $norm->process_name ?? 'Без процесса';
                        
                        // Создаём безопасное имя директории
                        $safeProcessName = preg_replace('/[^a-zA-Zа-яА-Я0-9\-\_\.\s]/ui', '_', $processName);
                        
                        // Путь к директории для документа
                        $processDir = $tempDir . '/' . $tabId . ' - ' . $tabName . '/' . $safeProcessName;
                        
                        // Создаем директорию процесса, если она не существует
                        if (!file_exists($processDir)) {
                            mkdir($processDir, 0755, true);
                            \Log::info('Создана директория процесса: ' . $processDir);
                        }
                        \Log::info('Документ ID: ' . $document->id . ' будет помещен в папку: ' . $processDir);
                    }
                    
                    // Формируем имя файла для сохранения
                    $originalName = $document->id . '_' . preg_replace('/[^a-zA-Zа-яА-Я0-9\._\-]/ui', '_', basename($document->file_path));
                    
                    // Копируем файл из хранилища во временную директорию
                    $originalPath = storage_path('app/' . $document->file_path);
                    
                    \Log::info('Проверка файла: ' . $originalPath . ' - ' . 
                        (file_exists($originalPath) ? 'существует, размер: ' . filesize($originalPath) . ' байт' : 'НЕ существует!'));
                    
                    if (file_exists($originalPath)) {
                        $destFile = $processDir . '/' . $originalName;
                        $copyResult = copy($originalPath, $destFile);
                        if ($copyResult) {
                            $copiedFilesCount++;
                            \Log::info('Успешно скопирован файл: ' . $originalPath . ' -> ' . $destFile);
                        } else {
                            $errorCopyCount++;
                            \Log::error('Ошибка при копировании файла: ' . $originalPath . ' -> ' . $destFile . 
                                ' (Ошибка: ' . (error_get_last() ? error_get_last()['message'] : 'Неизвестная ошибка') . ')');
                        }
                    } else {
                        \Log::warning('Пропущен несуществующий файл: ' . $originalPath . ' для документа ID: ' . $document->id);
                    }
                } catch (\Exception $e) {
                    $errorCopyCount++;
                    \Log::error('Ошибка при копировании файла ID ' . $document->id . ': ' . $e->getMessage() . "\n" . $e->getTraceAsString());
                    continue; // Продолжаем с другими файлами
                }
            }
            
            \Log::info('Итого скопировано файлов: ' . $copiedFilesCount . ', ошибок: ' . $errorCopyCount);
            
            // Создаем zip-архив
            $safeOrgName = preg_replace('/[^a-zA-Zа-яА-Я0-9\-\_\.\s]/ui', '_', $audit->organization->name);
            $zipFileName = $safeOrgName . '_audit_' . $audit->id . '_documents.zip';
            // Изменяем путь на более простой для отладки
            $zipFilePath = storage_path('app/temp/audit_' . $audit->id . '_docs.zip');
            \Log::info('Путь к архиву: ' . $zipFilePath);
            
            // Проверяем права на запись в директорию
            $tempDirPath = storage_path('app/temp');
            \Log::info('Проверка прав доступа к директории temp: ' . 
                (is_writable($tempDirPath) ? 'Директория доступна для записи' : 'Директория НЕ доступна для записи'));
            
            // Удаляем старый архив, если существует
            if (file_exists($zipFilePath)) {
                \Log::info('Старый архив существует, удаляем его');
                $unlinkResult = @unlink($zipFilePath);
                \Log::info('Результат удаления: ' . ($unlinkResult ? 'успешно' : 'ошибка - ' . (error_get_last() ? error_get_last()['message'] : 'неизвестная ошибка')));
            }
            
            $archiveCreated = false;
            
            // Пытаемся сначала использовать PowerShell
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                try {
                    \Log::info('Попытка создать архив через PowerShell (первичный метод)');
                    
                    // Проверяем существование временного каталога перед архивацией
                    \Log::info('Проверка существования директории: ' . $tempDir . ' - ' . (file_exists($tempDir) ? 'существует' : 'не существует'));
                    
                    // Проверяем содержимое директории
                    \Log::info('Содержимое директории для архивации:');
                    $files = scandir($tempDir);
                    foreach ($files as $file) {
                        \Log::info('Файл/Директория: ' . $file);
                    }
                    
                    // Создаем тестовый файл в директории, чтобы убедиться, что мы можем записывать туда
                    $testFilePath = $tempDir . '/test_before_archive.txt';
                    file_put_contents($testFilePath, 'Test content before archive');
                    \Log::info('Тестовый файл создан: ' . $testFilePath . ' - ' . (file_exists($testFilePath) ? 'существует' : 'не существует'));
                    
                    // Проверяем, что директория для архива существует
                    $tempDirPath = storage_path('app/temp');
                    if (!file_exists($tempDirPath)) {
                        \Log::info('Директория temp не существует, создаем...');
                        mkdir($tempDirPath, 0755, true);
                    }
                    
                    // Сначала попробуем простой тестовый вариант архивации
                    $testSourceDir = storage_path('app/temp/test_archive_dir');
                    $testDestZip = storage_path('app/temp/test_via_controller.zip');
                    
                    if (!file_exists($testSourceDir)) {
                        mkdir($testSourceDir, 0755, true);
                        file_put_contents($testSourceDir . '/test.txt', 'Test content from controller');
                    }
                    
                    $testCommand = 'powershell.exe -command "Compress-Archive -Path \'' . str_replace('/', '\\', $testSourceDir) . '\*\' -DestinationPath \'' . str_replace('/', '\\', $testDestZip) . '\' -Force"';
                    \Log::info('Тестовая команда PowerShell: ' . $testCommand);
                    
                    exec($testCommand, $testOutput, $testReturnCode);
                    \Log::info('Результат выполнения тестовой команды PowerShell: ' . $testReturnCode . ', вывод: ' . implode("\n", $testOutput));
                    \Log::info('Тестовый архив создан: ' . (file_exists($testDestZip) ? 'Да, размер: ' . filesize($testDestZip) . ' байт' : 'Нет'));
                    
                    // Теперь основная команда - на этот раз используем другой подход к формированию команды
                    // Используем новый синтаксис с кодом PowerShell в файле для надежности
                    $tempPowerShellScript = storage_path('app/temp/create_archive_' . uniqid() . '.ps1');
                    $scriptContent = 'param([string]$sourcePath, [string]$destPath)' . PHP_EOL;
                    $scriptContent .= 'Write-Host "Source path: $sourcePath"' . PHP_EOL;
                    $scriptContent .= 'Write-Host "Destination path: $destPath"' . PHP_EOL;
                    $scriptContent .= 'if (Test-Path $destPath) { Remove-Item $destPath -Force }' . PHP_EOL;
                    $scriptContent .= 'Write-Host "Files in source directory:"' . PHP_EOL;
                    $scriptContent .= 'Get-ChildItem -Path $sourcePath -Recurse | ForEach-Object { Write-Host $_.FullName }' . PHP_EOL;
                    $scriptContent .= 'Compress-Archive -Path ($sourcePath + "\*") -DestinationPath $destPath -Force' . PHP_EOL;
                    $scriptContent .= 'if (Test-Path $destPath) { Write-Host "SUCCESS: Archive created" } else { Write-Host "FAILED: Archive not created" }';
                    
                    file_put_contents($tempPowerShellScript, $scriptContent);
                    \Log::info('Создан временный скрипт PowerShell: ' . $tempPowerShellScript);
                    
                    $sourcePathParam = str_replace('/', '\\', $tempDir);
                    $destPathParam = str_replace('/', '\\', $zipFilePath);
                    
                    $command = 'powershell.exe -ExecutionPolicy Bypass -File "' . str_replace('/', '\\', $tempPowerShellScript) . '" -sourcePath "' . $sourcePathParam . '" -destPath "' . $destPathParam . '"';
                    \Log::info('Выполняемая команда: ' . $command);
                    
                    // Создаем тестовый файл перед выполнением команды
                    $testFile2Path = storage_path('app/temp/before_command_' . time() . '.txt');
                    file_put_contents($testFile2Path, 'Test before command execution');
                    \Log::info('Тестовый файл перед выполнением команды создан: ' . $testFile2Path);
                    
                    exec($command, $output, $returnCode);
                    \Log::info('Результат выполнения команды PowerShell: ' . $returnCode . ', вывод: ' . implode("\n", $output));
                    
                    // Удаляем временный скрипт
                    if (file_exists($tempPowerShellScript)) {
                        unlink($tempPowerShellScript);
                        \Log::info('Временный скрипт PowerShell удален');
                    }
                    
                    // Создаем тестовый файл после выполнения команды
                    $testFile3Path = storage_path('app/temp/after_command_' . time() . '.txt');
                    file_put_contents($testFile3Path, 'Test after command execution');
                    \Log::info('Тестовый файл после выполнения команды создан: ' . $testFile3Path);
                    
                    // Проверяем наличие архива
                    \Log::info('Проверка наличия архива: ' . $zipFilePath . ' - ' . (file_exists($zipFilePath) ? 'существует' : 'не существует'));
                    if (file_exists($zipFilePath)) {
                        \Log::info('Размер архива: ' . filesize($zipFilePath) . ' байт');
                    }
                    
                    if ($returnCode === 0 && file_exists($zipFilePath) && filesize($zipFilePath) > 0) {
                        \Log::info('Архив успешно создан через PowerShell');
                        $archiveCreated = true;
                    } else {
                        \Log::error('Ошибка при создании архива через PowerShell');
                    }
                } catch (\Exception $e) {
                    \Log::error('Исключение при создании архива через PowerShell: ' . $e->getMessage());
                }
            }
            
            // Если PowerShell не сработал, пробуем ZipArchive
            if (!$archiveCreated && class_exists('ZipArchive')) {
                try {
                    \Log::info('Начало создания архива через ZipArchive');
                    
                // Создаем архив с помощью ZipArchive
                $zip = new \ZipArchive();
                    $openResult = $zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                    \Log::info('Результат открытия архива: ' . ($openResult === true ? 'успешно' : 'код ошибки: ' . $openResult));
                    
                    if ($openResult !== true) {
                        $errorMessages = [
                            \ZipArchive::ER_EXISTS => 'Файл уже существует',
                            \ZipArchive::ER_INCONS => 'Zip-архив несовместим',
                            \ZipArchive::ER_INVAL => 'Недопустимый аргумент',
                            \ZipArchive::ER_MEMORY => 'Ошибка выделения памяти',
                            \ZipArchive::ER_NOENT => 'Файл не существует',
                            \ZipArchive::ER_NOZIP => 'Не zip-архив',
                            \ZipArchive::ER_OPEN => 'Не удалось открыть файл',
                            \ZipArchive::ER_READ => 'Ошибка чтения',
                            \ZipArchive::ER_SEEK => 'Ошибка позиционирования',
                        ];
                        $errorMessage = isset($errorMessages[$openResult]) ? $errorMessages[$openResult] : 'Неизвестная ошибка';
                        \Log::error('Расшифровка ошибки ZipArchive: ' . $errorMessage);
                        
                        // Проверяем, можем ли создать обычный файл в этой директории
                        $testFilePath = $tempDirPath . '/test_write_' . time() . '.txt';
                        $writeResult = @file_put_contents($testFilePath, 'test content');
                        \Log::info('Тест записи файла в директорию temp: ' . 
                            ($writeResult !== false ? 'Успешно - записано ' . $writeResult . ' байт' : 
                            'Ошибка - ' . (error_get_last() ? error_get_last()['message'] : 'неизвестная ошибка')));
                        
                        if ($writeResult !== false) {
                            @unlink($testFilePath);
                        }
                        
                        throw new \Exception("Не удалось создать архив, код ошибки: " . $openResult . " (" . $errorMessage . ")");
                }
                
                // Добавляем файлы в архив
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($tempDir),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );
                
                $filesAdded = false;
                    $fileCount = 0;
                
                foreach ($files as $name => $file) {
                    // Пропускаем директории и специальные файлы
                    if (!$file->isDir() && $file->getFilename() != '.' && $file->getFilename() != '..') {
                        // Получаем относительный путь
                        $filePath = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($tempDir) + 1);
                        
                        // Добавляем файл в архив
                            $addResult = $zip->addFile($filePath, $relativePath);
                            \Log::info('Добавление файла в архив: ' . $filePath . ' -> ' . $relativePath . ' => ' . ($addResult ? 'успешно' : 'ошибка'));
                            
                            if ($addResult) {
                        $filesAdded = true;
                                $fileCount++;
                            }
                    }
                }
                    
                    \Log::info('Всего файлов добавлено в архив: ' . $fileCount);
                
                if (!$filesAdded) {
                    throw new \Exception("Нет файлов для добавления в архив");
                }
                
                    $closeResult = $zip->close();
                    \Log::info('Результат закрытия архива: ' . ($closeResult ? 'успешно' : 'ошибка'));
                    
                    if (!$closeResult) {
                    throw new \Exception("Ошибка при закрытии архива");
                }
                    
                    if (file_exists($zipFilePath) && filesize($zipFilePath) > 0) {
                        \Log::info('Архив успешно создан через ZipArchive');
                        $archiveCreated = true;
                    }
            } catch (\Exception $e) {
                    \Log::error('Ошибка при создании архива через ZipArchive: ' . $e->getMessage());
                }
            }
            
            // Если предыдущие методы не сработали, пробуем другой вариант PowerShell
            if (!$archiveCreated && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                try {
                    \Log::info('Попытка создать архив через альтернативную команду PowerShell');
                    
                    // Создаем альтернативный временный скрипт
                    $altTempPowerShellScript = storage_path('app/temp/alt_create_archive_' . uniqid() . '.ps1');
                    $altScriptContent = 'param([string]$sourcePath, [string]$destPath)' . PHP_EOL;
                    $altScriptContent .= 'Add-Type -AssemblyName System.IO.Compression.FileSystem' . PHP_EOL;
                    $altScriptContent .= 'Write-Host "Trying ZipFile.CreateFromDirectory"' . PHP_EOL;
                    $altScriptContent .= 'Write-Host "Source: $sourcePath"' . PHP_EOL;
                    $altScriptContent .= 'Write-Host "Destination: $destPath"' . PHP_EOL;
                    $altScriptContent .= 'if (Test-Path $destPath) { Remove-Item $destPath -Force }' . PHP_EOL;
                    $altScriptContent .= 'try {' . PHP_EOL;
                    $altScriptContent .= '  [System.IO.Compression.ZipFile]::CreateFromDirectory($sourcePath, $destPath)' . PHP_EOL;
                    $altScriptContent .= '  if (Test-Path $destPath) { Write-Host "SUCCESS: Archive created with ZipFile" }' . PHP_EOL;
                    $altScriptContent .= '} catch {' . PHP_EOL;
                    $altScriptContent .= '  Write-Host "ERROR: $($_.Exception.Message)"' . PHP_EOL;
                    $altScriptContent .= '}' . PHP_EOL;
                    
                    file_put_contents($altTempPowerShellScript, $altScriptContent);
                    \Log::info('Создан альтернативный временный скрипт PowerShell: ' . $altTempPowerShellScript);
                    
                    $sourcePathParam = str_replace('/', '\\', $tempDir);
                    $destPathParam = str_replace('/', '\\', $zipFilePath);
                    
                    $command = 'powershell.exe -ExecutionPolicy Bypass -File "' . str_replace('/', '\\', $altTempPowerShellScript) . '" -sourcePath "' . $sourcePathParam . '" -destPath "' . $destPathParam . '"';
                    \Log::info('Выполняемая команда PowerShell (альтернативная): ' . $command);
                    
                    // Вместо создания файла вручную, мы используем скрипт
                    exec($command, $output, $returnCode);
                    \Log::info('Результат выполнения альтернативной команды PowerShell: ' . $returnCode . ', вывод: ' . implode("\n", $output));
                    
                    // Удаляем временный скрипт
                    if (file_exists($altTempPowerShellScript)) {
                        unlink($altTempPowerShellScript);
                        \Log::info('Альтернативный временный скрипт PowerShell удален');
                    }
                    
                    // Проверяем, создался ли файл
                    if (file_exists($zipFilePath)) {
                        \Log::info('Тестовый файл создан успешно: ' . $zipFilePath . ', размер: ' . filesize($zipFilePath) . ' байт');
                        
                        // Теперь пробуем создать zip с использованием другой команды PowerShell
                        $command = "powershell.exe -command \"Add-Type -AssemblyName System.IO.Compression.FileSystem; [System.IO.Compression.ZipFile]::CreateFromDirectory('" . 
                            str_replace('/', '\\', $tempDir) . "', '" . str_replace('/', '\\', $zipFilePath) . "')\"";
                        \Log::info('Выполняемая команда PowerShell (альтернативная): ' . $command);
                        
                    exec($command, $output, $returnCode);
                        \Log::info('Результат выполнения альтернативной команды PowerShell: ' . $returnCode . ', вывод: ' . implode("\n", $output));
                        
                        if ($returnCode === 0 && file_exists($zipFilePath) && filesize($zipFilePath) > 0) {
                            \Log::info('Архив успешно создан через альтернативную команду PowerShell');
                            $archiveCreated = true;
                        } else {
                            \Log::error('Ошибка при создании архива через альтернативную команду PowerShell');
                        }
                    } else {
                        \Log::error('Не удалось создать тестовый файл для архива');
                    }
                } catch (\Exception $e) {
                    \Log::error('Исключение при создании архива через альтернативную команду PowerShell: ' . $e->getMessage());
                }
            }
            
            // Проверяем создание архива
            $archiveExists = file_exists($zipFilePath);
            \Log::info('Проверка существования архива: ' . ($archiveExists ? 'архив создан' : 'архив не создан'));
            $archiveSize = $archiveExists ? filesize($zipFilePath) : 0;
            \Log::info('Размер архива: ' . $archiveSize . ' байт');
            
            if (!$archiveCreated || !$archiveExists || $archiveSize === 0) {
                \Log::error('Архив не был создан или имеет нулевой размер: ' . $zipFilePath);
                
                // Последняя попытка - создание простого текстового файла вместо архива для тестирования
                $simpleFileName = 'test_file_' . time() . '.txt';
                $simpleFilePath = storage_path('app/temp/' . $simpleFileName);
                $simpleContent = "Это тестовый файл вместо архива для аудита #" . $audit->id . ".\nВремя создания: " . date('Y-m-d H:i:s');
                
                $writeResult = @file_put_contents($simpleFilePath, $simpleContent);
                \Log::info('Создание простого текстового файла для тестирования: ' . 
                    ($writeResult !== false ? 'Успешно - создан файл ' . $simpleFilePath . ' размером ' . $writeResult . ' байт' : 
                    'Ошибка - ' . (error_get_last() ? error_get_last()['message'] : 'неизвестная ошибка')));
                
                if ($writeResult !== false && file_exists($simpleFilePath)) {
                    \Log::info('Используем простой текстовый файл вместо архива для тестирования');
                    $zipFilePath = $simpleFilePath;
                    $zipFileName = $simpleFileName;
                } else {
                    return back()->with('error', 'Не удалось создать архив документов. Пожалуйста, проверьте права доступа и наличие необходимых расширений PHP.');
                }
            }
            
            // Очищаем временную директорию
            $this->deleteDirectory($tempDir);
            \Log::info('Временная директория удалена');
            
            // Обходим буферизацию (с безопасной проверкой)
            $bufferLevels = ob_get_level();
            \Log::info('Уровней буферизации перед очисткой: ' . $bufferLevels);
            
            while (ob_get_level() > 0) {
            ob_end_clean();
            }
            
            \Log::info('Буферы очищены, начинаем отправку файла');
            \Log::info('Размер файла для отправки: ' . filesize($zipFilePath) . ' байт');
            
            // Отправляем архив пользователю с явными заголовками
            \Log::info('Отправка файла пользователю: ' . $zipFilePath . ', имя файла: ' . $zipFileName);
            
            // Используем более простое имя файла без кириллицы
            $simpleFileName = 'archive_' . $audit->id . '.zip';
            if (pathinfo($zipFilePath, PATHINFO_EXTENSION) !== 'zip') {
                $simpleFileName = 'archive_' . $audit->id . '.' . pathinfo($zipFilePath, PATHINFO_EXTENSION);
            }
            \Log::info('Упрощенное имя файла для заголовков: ' . $simpleFileName);
            
            // Определяем тип содержимого
            $contentType = 'application/octet-stream';
            if (pathinfo($zipFilePath, PATHINFO_EXTENSION) === 'zip') {
                $contentType = 'application/zip';
            } else if (pathinfo($zipFilePath, PATHINFO_EXTENSION) === 'txt') {
                $contentType = 'text/plain';
            }
            
            return response()->file($zipFilePath, [
                'Content-Type' => $contentType,
                'Content-Length' => filesize($zipFilePath),
                'Content-Disposition' => 'attachment; filename="' . $simpleFileName . '"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ])->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            \Log::error('Исключение при скачивании архива: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Произошла ошибка при создании архива: ' . $e->getMessage());
        }
    }
    
    /**
     * Скачать архив документов для определенной вкладки аудита
     * 
     * @param Audit $audit
     * @param int $tab
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadTabDocumentsArchive(Audit $audit, $process, $tab)
    {
        // Проверьте, что метод правильно находит файлы и создает архив
        // Добавьте логирование для отладки
        \Log::info("Attempting to download documents for audit: {$audit->id}, process: {$process}, tab: {$tab}");
        
        try {
            \Log::info('Начало скачивания архива для аудита ID: ' . $audit->id . ', процесс: ' . $process . ', вкладка: ' . $tab);
            
            // Получаем все нормы для данной вкладки
            $norms = Norm::where('tab_id', $tab)->get()->pluck('id')->toArray();
            \Log::info('Найдено норм для вкладки ' . $tab . ': ' . count($norms));
            
            if (empty($norms)) {
                \Log::warning('Нормы для этой вкладки не найдены: ' . $tab);
                return back()->with('error', 'Нормы для этой вкладки не найдены');
            }
            
            // Получаем документы, относящиеся к нормам этой вкладки
            $documents = NormDocument::where('audit_id', $audit->id)
                ->whereIn('norm_id', $norms)
                ->get();
            
            \Log::info('Найдено документов для вкладки ' . $tab . ': ' . $documents->count());
            
            if ($documents->isEmpty()) {
                \Log::warning('Документы для этой вкладки не найдены: ' . $tab);
                return back()->with('error', 'Документы для этой вкладки не найдены');
            }
            
            // Создаем временную директорию для файлов
            $tempDir = storage_path('app/temp/archives/' . uniqid());
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
                \Log::info('Создана временная директория: ' . $tempDir);
            }
            
            // Получаем название вкладки
            $tabs = $this->getTabs();
            $tabName = $tabs[$tab] ?? 'Вкладка ' . $tab;
            \Log::info('Название вкладки: ' . $tabName);
            
            // Копируем файлы в соответствующие папки
            $copiedFilesCount = 0;
            $errorCopyCount = 0;
            
            foreach ($documents as $document) {
                try {
                    \Log::info('Обработка документа ID: ' . $document->id . ', путь: ' . $document->file_path);
                    
                    // Получаем норму, связанную с документом
                    $norm = $document->norm;
                    
                    if (!$norm) {
                        \Log::warning('Норма не найдена для документа ID: ' . $document->id . '. Пропускаем...');
                        continue;
                    }
                    
                    // Определяем процесс для этой нормы
                    $processName = $norm->process_name ?? 'Без процесса';
                    
                    // Создаём безопасное имя директории
                    $safeProcessName = preg_replace('/[^a-zA-Zа-яА-Я0-9\-\_\.\s]/ui', '_', $processName);
                    
                    // Путь к директории для документа
                    $processDir = $tempDir . '/' . $safeProcessName;
                    
                    // Создаем директорию процесса, если она не существует
                    if (!file_exists($processDir)) {
                        mkdir($processDir, 0755, true);
                        \Log::info('Создана директория процесса: ' . $processDir);
                    }
                    
                    // Формируем имя файла для сохранения
                    $originalName = $document->id . '_' . preg_replace('/[^a-zA-Zа-яА-Я0-9\._\-]/ui', '_', basename($document->file_path));
                    
                    // Копируем файл из хранилища во временную директорию
                    $originalPath = storage_path('app/' . $document->file_path);
                    
                    \Log::info('Проверка файла: ' . $originalPath . ' - ' . 
                        (file_exists($originalPath) ? 'существует, размер: ' . filesize($originalPath) . ' байт' : 'НЕ существует!'));
                    
                    if (file_exists($originalPath)) {
                        $destFile = $processDir . '/' . $originalName;
                        $copyResult = copy($originalPath, $destFile);
                        if ($copyResult) {
                            $copiedFilesCount++;
                            \Log::info('Успешно скопирован файл: ' . $originalPath . ' -> ' . $destFile);
                        } else {
                            $errorCopyCount++;
                            \Log::error('Ошибка при копировании файла: ' . $originalPath . ' -> ' . $destFile . 
                                ' (Ошибка: ' . (error_get_last() ? error_get_last()['message'] : 'Неизвестная ошибка') . ')');
                        }
                    } else {
                        \Log::warning('Пропущен несуществующий файл: ' . $originalPath . ' для документа ID: ' . $document->id);
                    }
                } catch (\Exception $e) {
                    $errorCopyCount++;
                    \Log::error('Ошибка при копировании файла ID ' . $document->id . ': ' . $e->getMessage() . "\n" . $e->getTraceAsString());
                    continue; // Продолжаем с другими файлами
                }
            }
            
            \Log::info('Итого скопировано файлов: ' . $copiedFilesCount . ', ошибок: ' . $errorCopyCount);
            
            if ($copiedFilesCount == 0) {
                \Log::warning('Не удалось скопировать ни одного файла для вкладки ' . $tab);
                return back()->with('error', 'Не удалось найти документы для скачивания');
            }
            
            // Создаем zip-архив
            $safeOrgName = preg_replace('/[^a-zA-Zа-яА-Я0-9\-\_\.\s]/ui', '_', $audit->organization->name);
            $zipFileName = $safeOrgName . '_audit_' . $audit->id . '_tab_' . $tab . '_' . $tabName . '.zip';
            $zipFilePath = storage_path('app/temp/tab_' . $audit->id . '_' . $tab . '_docs.zip');
            \Log::info('Путь к архиву: ' . $zipFilePath);
            
            // Удаляем старый архив, если существует
            if (file_exists($zipFilePath)) {
                \Log::info('Старый архив существует, удаляем его');
                $unlinkResult = @unlink($zipFilePath);
                \Log::info('Результат удаления: ' . ($unlinkResult ? 'успешно' : 'ошибка - ' . (error_get_last() ? error_get_last()['message'] : 'неизвестная ошибка')));
            }
            
            $archiveCreated = false;
            
            // Используем тот же подход с PowerShell скриптом, который работает в другом методе
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                try {
                    \Log::info('Создание архива вкладки через PowerShell');
                    
                    $tempPowerShellScript = storage_path('app/temp/create_tab_archive_' . uniqid() . '.ps1');
                    $scriptContent = 'param([string]$sourcePath, [string]$destPath)' . PHP_EOL;
                    $scriptContent .= 'Write-Host "Source path: $sourcePath"' . PHP_EOL;
                    $scriptContent .= 'Write-Host "Destination path: $destPath"' . PHP_EOL;
                    $scriptContent .= 'if (Test-Path $destPath) { Remove-Item $destPath -Force }' . PHP_EOL;
                    $scriptContent .= 'Write-Host "Files in source directory:"' . PHP_EOL;
                    $scriptContent .= 'Get-ChildItem -Path $sourcePath -Recurse | ForEach-Object { Write-Host $_.FullName }' . PHP_EOL;
                    $scriptContent .= 'Compress-Archive -Path ($sourcePath + "\*") -DestinationPath $destPath -Force' . PHP_EOL;
                    $scriptContent .= 'if (Test-Path $destPath) { Write-Host "SUCCESS: Archive created" } else { Write-Host "FAILED: Archive not created" }';
                    
                    file_put_contents($tempPowerShellScript, $scriptContent);
                    \Log::info('Создан временный скрипт PowerShell для вкладки: ' . $tempPowerShellScript);
                    
                    $sourcePathParam = str_replace('/', '\\', $tempDir);
                    $destPathParam = str_replace('/', '\\', $zipFilePath);
                    
                    $command = 'powershell.exe -ExecutionPolicy Bypass -File "' . str_replace('/', '\\', $tempPowerShellScript) . '" -sourcePath "' . $sourcePathParam . '" -destPath "' . $destPathParam . '"';
                    \Log::info('Выполняемая команда: ' . $command);
                    
                    exec($command, $output, $returnCode);
                    \Log::info('Результат выполнения команды PowerShell: ' . $returnCode . ', вывод: ' . implode("\n", $output));
                    
                    // Удаляем временный скрипт
                    if (file_exists($tempPowerShellScript)) {
                        unlink($tempPowerShellScript);
                        \Log::info('Временный скрипт PowerShell удален');
                    }
                    
                    if ($returnCode === 0 && file_exists($zipFilePath) && filesize($zipFilePath) > 0) {
                        \Log::info('Архив вкладки успешно создан через PowerShell');
                        $archiveCreated = true;
                    } else {
                        \Log::error('Ошибка при создании архива вкладки через PowerShell');
                    }
                } catch (\Exception $e) {
                    \Log::error('Исключение при создании архива вкладки через PowerShell: ' . $e->getMessage());
                }
            }
            
            // Если PowerShell не сработал, пробуем ZipArchive
            if (!$archiveCreated && class_exists('ZipArchive')) {
                try {
                    \Log::info('Начало создания архива вкладки через ZipArchive');
                    
                // Создаем архив с помощью ZipArchive
                $zip = new \ZipArchive();
                    $openResult = $zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                    \Log::info('Результат открытия архива: ' . ($openResult === true ? 'успешно' : 'код ошибки: ' . $openResult));
                    
                    if ($openResult !== true) {
                        $errorMessages = [
                            \ZipArchive::ER_EXISTS => 'Файл уже существует',
                            \ZipArchive::ER_INCONS => 'Zip-архив несовместим',
                            \ZipArchive::ER_INVAL => 'Недопустимый аргумент',
                            \ZipArchive::ER_MEMORY => 'Ошибка выделения памяти',
                            \ZipArchive::ER_NOENT => 'Файл не существует',
                            \ZipArchive::ER_NOZIP => 'Не zip-архив',
                            \ZipArchive::ER_OPEN => 'Не удалось открыть файл',
                            \ZipArchive::ER_READ => 'Ошибка чтения',
                            \ZipArchive::ER_SEEK => 'Ошибка позиционирования',
                        ];
                        $errorMessage = isset($errorMessages[$openResult]) ? $errorMessages[$openResult] : 'Неизвестная ошибка';
                        \Log::error('Расшифровка ошибки ZipArchive: ' . $errorMessage);
                        
                        // Проверяем, можем ли создать обычный файл в этой директории
                        $testFilePath = $tempDirPath . '/test_write_' . time() . '.txt';
                        $writeResult = @file_put_contents($testFilePath, 'test content');
                        \Log::info('Тест записи файла в директорию temp: ' . 
                            ($writeResult !== false ? 'Успешно - записано ' . $writeResult . ' байт' : 
                            'Ошибка - ' . (error_get_last() ? error_get_last()['message'] : 'неизвестная ошибка')));
                        
                        if ($writeResult !== false) {
                            @unlink($testFilePath);
                        }
                        
                        throw new \Exception("Не удалось создать архив, код ошибки: " . $openResult . " (" . $errorMessage . ")");
                }
                
                // Добавляем файлы в архив
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($tempDir),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );
                
                $filesAdded = false;
                
                foreach ($files as $name => $file) {
                    // Пропускаем директории и специальные файлы
                    if (!$file->isDir() && $file->getFilename() != '.' && $file->getFilename() != '..') {
                        // Получаем относительный путь
                        $filePath = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($tempDir) + 1);
                        
                        // Добавляем файл в архив
                            $addResult = $zip->addFile($filePath, $relativePath);
                            \Log::info('Добавление файла в архив: ' . $filePath . ' -> ' . $relativePath . ' => ' . ($addResult ? 'успешно' : 'ошибка'));
                            
                            if ($addResult) {
                        $filesAdded = true;
                            }
                    }
                }
                    
                    \Log::info('Всего файлов добавлено в архив: ' . ($filesAdded ? $filesAdded : 0));
                
                if (!$filesAdded) {
                    throw new \Exception("Нет файлов для добавления в архив");
                }
                
                    $closeResult = $zip->close();
                    \Log::info('Результат закрытия архива: ' . ($closeResult ? 'успешно' : 'ошибка'));
                    
                    if (!$closeResult) {
                    throw new \Exception("Ошибка при закрытии архива");
                }
                    
                    if (file_exists($zipFilePath) && filesize($zipFilePath) > 0) {
                        \Log::info('Архив успешно создан через ZipArchive');
                        $archiveCreated = true;
                    }
                } catch (\Exception $e) {
                    \Log::error('Ошибка при создании архива через ZipArchive: ' . $e->getMessage());
                }
            }
            
            // Проверяем создание архива
            $archiveExists = file_exists($zipFilePath);
            \Log::info('Проверка существования архива: ' . ($archiveExists ? 'архив создан' : 'архив не создан'));
            $archiveSize = $archiveExists ? filesize($zipFilePath) : 0;
            \Log::info('Размер архива: ' . $archiveSize . ' байт');
            
            if (!$archiveCreated || !$archiveExists || $archiveSize === 0) {
                \Log::error('Архив не был создан или имеет нулевой размер: ' . $zipFilePath);
                
                // Последняя попытка - создание простого текстового файла вместо архива для тестирования
                $simpleFileName = 'test_file_' . time() . '.txt';
                $simpleFilePath = storage_path('app/temp/' . $simpleFileName);
                $simpleContent = "Это тестовый файл вместо архива для аудита #" . $audit->id . ".\nВремя создания: " . date('Y-m-d H:i:s');
                
                $writeResult = @file_put_contents($simpleFilePath, $simpleContent);
                \Log::info('Создание простого текстового файла для тестирования: ' . 
                    ($writeResult !== false ? 'Успешно - создан файл ' . $simpleFilePath . ' размером ' . $writeResult . ' байт' : 
                    'Ошибка - ' . (error_get_last() ? error_get_last()['message'] : 'неизвестная ошибка')));
                
                if ($writeResult !== false && file_exists($simpleFilePath)) {
                    \Log::info('Используем простой текстовый файл вместо архива для тестирования');
                    $zipFilePath = $simpleFilePath;
                    $zipFileName = $simpleFileName;
                } else {
                    return back()->with('error', 'Не удалось создать архив документов. Пожалуйста, проверьте права доступа и наличие необходимых расширений PHP.');
                }
            }
            
            // Очищаем временную директорию
            $this->deleteDirectory($tempDir);
            \Log::info('Временная директория удалена');
            
            // Обходим буферизацию (с безопасной проверкой)
            $bufferLevels = ob_get_level();
            \Log::info('Уровней буферизации перед очисткой: ' . $bufferLevels);
            
            while (ob_get_level() > 0) {
            ob_end_clean();
            }
            
            \Log::info('Буферы очищены, начинаем отправку файла');
            \Log::info('Размер файла для отправки: ' . filesize($zipFilePath) . ' байт');
            
            // Отправляем архив пользователю с явными заголовками
            \Log::info('Отправка файла пользователю: ' . $zipFilePath . ', имя файла: ' . $zipFileName);
            
            // Используем более простое имя файла без кириллицы
            $simpleFileName = 'tab_archive_' . $audit->id . '_' . $tab . '.zip';
            \Log::info('Упрощенное имя файла для заголовков: ' . $simpleFileName);
            
            // Определяем тип содержимого
            $contentType = 'application/octet-stream';
            if (pathinfo($zipFilePath, PATHINFO_EXTENSION) === 'zip') {
                $contentType = 'application/zip';
            } else if (pathinfo($zipFilePath, PATHINFO_EXTENSION) === 'txt') {
                $contentType = 'text/plain';
            }
            
            return response()->file($zipFilePath, [
                'Content-Type' => $contentType,
                'Content-Length' => filesize($zipFilePath),
                'Content-Disposition' => 'attachment; filename="' . $simpleFileName . '"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ])->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            \Log::error('Ошибка при скачивании архива вкладки: ' . $e->getMessage());
            return back()->with('error', 'Произошла ошибка при создании архива: ' . $e->getMessage());
        }
    }
    
    /**
     * Рекурсивное удаление директории с файлами
     * 
     * @param string $dir
     * @return bool
     */
    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }
        
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            
            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        
        return rmdir($dir);
    }

    /**
     * Копирование аудита
     * 
     * @param Audit $audit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function copyAudit(Audit $audit)
    {
        try {
            \Log::info('Начало копирования аудита ID: ' . $audit->id);
            
            // Создаем новый аудит на основе существующего
            $newAudit = $audit->replicate();
            $newAudit->title = 'Копия - ' . $audit->title;
            $newAudit->status = 'in_progress'; // Устанавливаем статус "В процессе"
            $newAudit->start_date = now(); // Устанавливаем текущую дату как дату начала
            $newAudit->end_date = null; // Сбрасываем дату окончания
            $newAudit->created_at = now();
            $newAudit->updated_at = now();
            $newAudit->save();
            
            \Log::info('Создан новый аудит ID: ' . $newAudit->id);
            
            // Копируем оценки норм (если нужно)
            if (request('copy_assessments', false)) {
                $assessments = NormAssessment::where('audit_id', $audit->id)->get();
                
                foreach ($assessments as $assessment) {
                    $newAssessment = $assessment->replicate();
                    $newAssessment->audit_id = $newAudit->id;
                    $newAssessment->created_at = now();
                    $newAssessment->updated_at = now();
                    $newAssessment->save();
                }
                
                \Log::info('Скопировано оценок: ' . $assessments->count());
            }
            
            // Копируем документы (если нужно)
            if (request('copy_documents', false)) {
                $documents = NormDocument::where('audit_id', $audit->id)->get();
                $copiedCount = 0;
                
                foreach ($documents as $document) {
                    // Проверяем существование файла
                    $originalPath = storage_path('app/' . $document->file_path);
                    
                    if (file_exists($originalPath)) {
                        // Создаем новый путь для копии файла
                        $pathInfo = pathinfo($document->file_path);
                        $newRelativePath = $pathInfo['dirname'] . '/' . $newAudit->id . '_' . uniqid() . '.' . $pathInfo['extension'];
                        $newFullPath = storage_path('app/' . $newRelativePath);
                        
                        // Копируем файл
                        if (copy($originalPath, $newFullPath)) {
                            // Создаем новую запись документа
                            $newDocument = $document->replicate();
                            $newDocument->audit_id = $newAudit->id;
                            $newDocument->file_path = $newRelativePath;
                            $newDocument->created_at = now();
                            $newDocument->updated_at = now();
                            $newDocument->save();
                            
                            $copiedCount++;
                        }
                    }
                }
                
                \Log::info('Скопировано документов: ' . $copiedCount);
            }
            
            // Копируем свидетельства (если нужно)
            if (request('copy_evidences', false)) {
                $evidences = Evidence::where('audit_id', $audit->id)->get();
                $copiedCount = 0;
                
                foreach ($evidences as $evidence) {
                    // Проверяем существование файла
                    $originalPath = storage_path('app/' . $evidence->file_path);
                    
                    if (file_exists($originalPath)) {
                        // Создаем новый путь для копии файла
                        $pathInfo = pathinfo($evidence->file_path);
                        $newRelativePath = $pathInfo['dirname'] . '/' . $newAudit->id . '_' . uniqid() . '.' . $pathInfo['extension'];
                        $newFullPath = storage_path('app/' . $newRelativePath);
                        
                        // Копируем файл
                        if (copy($originalPath, $newFullPath)) {
                            // Создаем новую запись свидетельства
                            $newEvidence = $evidence->replicate();
                            $newEvidence->audit_id = $newAudit->id;
                            $newEvidence->file_path = $newRelativePath;
                            $newEvidence->created_at = now();
                            $newEvidence->updated_at = now();
                            $newEvidence->save();
                            
                            $copiedCount++;
                        }
                    }
                }
                
                \Log::info('Скопировано свидетельств: ' . $copiedCount);
            }
            
            // Возвращаемся на страницу списка аудитов вместо редактирования
            return redirect()->route('organizations.audits.index', ['organization' => $audit->organization_id])
                ->with('success', 'Аудит успешно скопирован.');
            
        } catch (\Exception $e) {
            \Log::error('Ошибка при копировании аудита: ' . $e->getMessage());
            return back()->with('error', 'Ошибка при копировании аудита: ' . $e->getMessage());
        }
    }
} 