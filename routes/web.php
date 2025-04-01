<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\NormController;
use App\Http\Controllers\NormDocumentController;
use App\Http\Controllers\AuditSystemController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProcessController;
use Illuminate\Support\Facades\Log;

// Редирект с главной на организации для авторизованных пользователей
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('organizations.index');
    }
    return view('welcome');
});

// Маршруты для гостей
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Основная группа маршрутов для авторизованных пользователей
Route::middleware(['auth'])->group(function () {
    // Редирект с /dashboard на организации
    Route::get('/dashboard', function() {
        return redirect()->route('organizations.index');
    })->name('dashboard');
    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::resource('organizations', OrganizationController::class);
    Route::get('/organizations/{organization}/edit', [OrganizationController::class, 'edit'])->name('organizations.edit');
    Route::resource('organizations.audits', AuditController::class);
    Route::get('/norms', [NormController::class, 'index'])->name('norms.index');
    Route::get('/organizations/{organization}/audits/{audit}/conduct', [AuditController::class, 'conduct'])
        ->name('organizations.audits.conduct');
    Route::get('/organizations/{organization}/audits/{audit}/print/{tab}', [AuditController::class, 'print'])
        ->name('organizations.audits.print');
    Route::get('/organizations/{organization}/audits/{audit}/list/{tab}', [AuditController::class, 'list'])
        ->name('organizations.audits.list');
    Route::get('/audit/{audit}/measure/{norm}', [AuditController::class, 'measure'])
        ->name('audit.measure');
    Route::post('organizations/{organization}/audits/{audit}/norms/{norm}/documents', [NormDocumentController::class, 'store'])
        ->name('norm.documents.store');
    Route::put('/norm-documents/{document}', [NormDocumentController::class, 'update'])
        ->name('norm.documents.update');
    Route::delete('/norm-documents/{document}', [NormDocumentController::class, 'destroy'])
        ->name('norm.documents.destroy');
    Route::get('/norm-documents/{document}/download', [NormDocumentController::class, 'download'])
        ->name('norm.documents.download');
    Route::post('/organizations/{organization}/audits/{audit}/assessments', [AuditController::class, 'saveAssessment'])
        ->name('audit.saveAssessment')
        ->middleware('auth');
    Route::post('/organizations/{organization}/audits/{audit}/approvals', [AuditController::class, 'updateApproval'])
        ->name('organizations.audits.approvals.update');
    Route::get('/organizations/{organization}/audits/{audit}/norms', [AuditController::class, 'getNorms'])
        ->name('organizations.audits.norms');

    // Добавляем маршруты для систем аудита
    Route::get('/auditsystems/create', [AuditSystemController::class, 'create'])
        ->name('auditsystems.create');
    Route::post('/auditsystems', [AuditSystemController::class, 'store'])
        ->name('auditsystems.store');
    Route::get('/auditsystems/{auditSystem}/edit', [AuditSystemController::class, 'edit'])
        ->name('auditsystems.edit');

    // Добавляем маршруты для процессов аудита
    Route::get('/audits/{audit}/process/{process}', [AuditController::class, 'process'])
        ->name('audit.process');

    Route::get('/organizations/{organization}/audits/{audit}/norms/{norm}/documents/create', 
        [NormDocumentController::class, 'create'])->name('norm.documents.create');

    Route::get('/audit/{auditId}/process/{processId}', [AuditController::class, 'showProcess'])->name('showProcess');
    Route::post('/audit/{auditId}/process/{processId}/save', [AuditController::class, 'saveAssessment'])->name('saveAssessment');

    Route::get('/audit/{audit}/itogotm', [AuditController::class, 'itogoTm'])->name('audit.itogotm');

    // Маршруты для вкладки B14
    Route::get('/audit/{audit}/recount', [AuditController::class, 'recount'])->name('audit.recount');
    Route::get('/audit/{audit}/stop', [AuditController::class, 'stop'])->name('audit.stop');

    Route::post('/assessments/{norm}', [AssessmentController::class, 'store'])->name('assessments.store');
    Route::post('/assessments/{norm}/evidence', [AssessmentController::class, 'storeEvidence'])->name('assessments.evidence.store');

    // Добавляем новые маршруты для print и list
    Route::get('/audit/{audit}/process/{tab}/print', [AuditController::class, 'print'])
        ->name('audit.print')
        ->middleware('auth');
    Route::get('/audit/{audit}/process/{tab}/list', [AuditController::class, 'list'])
        ->name('audit.list')
        ->middleware('auth');

    Route::post('/audit/assessment/save', [AssessmentController::class, 'store'])
        ->name('assessment.store');

    Route::get('/organizations/{organization}/audits/{audit}/edit', [AuditController::class, 'edit'])
        ->name('organizations.audits.edit');
    Route::put('/organizations/{organization}/audits/{audit}', [AuditController::class, 'update'])
        ->name('organizations.audits.update');

    // Маршруты для печати
    Route::get('/audit/{audit}/process/{process}/tab/{tab}/print', [AuditController::class, 'printTab'])
        ->name('audit.print');
    
    Route::get('/audit/{audit}/process/{process}/tab/{tab}/list', [AuditController::class, 'printList'])
        ->name('audit.list');

    Route::post('/save-score', [AssessmentController::class, 'store'])->name('assessment.store');

    // Маршрут для итоговых оценок (B14 Оценки)
    Route::get('/audit/{audit}/process/15', [AuditController::class, 'tab14'])->name('audit.tab14');

    Route::get('/api/process-scores/load', [AuditController::class, 'loadProcessScores'])
        ->name('process.scores.load')
        ->middleware('auth');

    Route::get('/organizations/{organization}/audits/{audit}/conduct/evidence/{norm?}', [AuditController::class, 'showEvidence'])->name('audit.evidence');
    Route::post('/organizations/{organization}/audits/{audit}/conduct/evidence', [AuditController::class, 'storeEvidence'])->name('audit.evidence.store');

    Route::get('/audit/{audit}/report', [ReportController::class, 'generateReport'])
        ->name('audit.report');

    // Маршрут для страницы с документами аудита
    Route::get('/audits/{audit}/process/{process}/tab/{tab}/documents', [AuditController::class, 'documents'])
        ->name('audit.documents');

    Route::get('/organizations/{organization}/audits/{audit}/evidence/{norm?}/{process?}/{tab?}', 
        [AuditController::class, 'showEvidence'])->name('audit.evidence');

    // Маршрут для печати свидетельств
    Route::get('/audit/{audit}/process/{process}/tab/{tab}/print-evidence', [AuditController::class, 'printEvidence'])
        ->name('audit.print.evidence');

    Route::get('/audit/{audit}/print/full', [AuditController::class, 'printFullReport'])->name('audit.print.full');

    Route::get('/audit/{audit}/download/word', [AuditController::class, 'downloadWordReport'])->name('audit.download.word');

    Route::get('/audit/{audit}/download/random-word', [AuditController::class, 'downloadRandomWordReport'])->name('audit.download.random.word');

    // Маршрут для скачивания архива всех документов
    Route::get('/audit/{audit}/download/documents', [AuditController::class, 'downloadDocumentsArchive'])->name('audit.download.documents');

    // Маршрут для скачивания архива документов по вкладке
    Route::get('/audit/{audit}/process/{process}/tab/{tab}/download/documents', [AuditController::class, 'downloadTabDocumentsArchive'])->name('audit.download.tab.documents');

    // Маршрут для создания тестового документа
    Route::get('/audit/{audit}/create-test-document', [NormDocumentController::class, 'createTestDocument'])->name('audit.create.test.document');

    // Маршруты для управления доступами
    Route::post('/access/audit', [AccessController::class, 'storeAuditAccess'])->name('access.audit.store');
    Route::post('/access/audit/update', [AccessController::class, 'updateAuditAccesses'])->name('access.audit.update');
    Route::delete('/access/{access}/audit/{audit}', [AccessController::class, 'destroyAuditAccess'])
        ->name('access.audit.destroy');

    // Маршруты для организаций
    Route::prefix('organizations')->name('organizations.')->group(function () {
        // Маршруты для аудитов
        Route::prefix('{organization}/audits')->name('audits.')->group(function () {
            Route::get('/', [AuditController::class, 'index'])->name('index');
            Route::post('/', [AuditController::class, 'store'])->name('store');
            Route::get('/create', [AuditController::class, 'create'])->name('create');
            Route::get('/{audit}', [AuditController::class, 'show'])->name('show');
            Route::get('/{audit}/edit', [AuditController::class, 'edit'])->name('edit');
            Route::put('/{audit}', [AuditController::class, 'update'])->name('update');
            Route::delete('/{audit}', [AuditController::class, 'destroy'])->name('destroy');
            
            // Маршрут для проведения аудита
            Route::get('/{audit}/conduct', [AuditController::class, 'conduct'])->name('conduct');
            
            // Маршруты для работы со свидетельствами
            Route::get('/{audit}/evidence/{norm?}', [AuditController::class, 'showEvidence'])->name('evidence');
            Route::post('/{audit}/evidence', [AuditController::class, 'storeEvidence'])->name('evidence.store');
        });
    });

    // Маршрут для копирования аудита
    Route::get('/audit/{audit}/copy', [AuditController::class, 'copyAudit'])->name('audit.copy');
});

// Маршруты для управления аудитами
Route::middleware(['auth', \App\Http\Middleware\CheckAccess::class])->group(function () {
    // Базовые маршруты
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Маршруты для организаций
    Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations.index');
    
    // Маршруты для организаций и аудитов
    Route::prefix('organizations')->name('organizations.')->group(function () {
        Route::get('/{organization}', [OrganizationController::class, 'show'])->name('show');
        
        // Маршруты для аудитов
        Route::prefix('{organization}/audits')->name('audits.')->group(function () {
            Route::get('/', [AuditController::class, 'index'])->name('index');
            Route::get('/create', [AuditController::class, 'create'])->name('create');
            Route::post('/', [AuditController::class, 'store'])->name('store');
            Route::get('/{audit}', [AuditController::class, 'show'])->name('show');
            Route::get('/{audit}/edit', [AuditController::class, 'edit'])->name('edit');
            Route::put('/{audit}', [AuditController::class, 'update'])->name('update');
            Route::delete('/{audit}', [AuditController::class, 'destroy'])->name('destroy');
            
            // Маршрут для проведения аудита
            Route::get('/{audit}/conduct', [AuditController::class, 'conduct'])->name('conduct');
            
            // Маршруты для работы со свидетельствами
            Route::get('/{audit}/evidence/{norm?}', [AuditController::class, 'showEvidence'])->name('evidence');
            Route::post('/{audit}/evidence', [AuditController::class, 'storeEvidence'])->name('evidence.store');
        });
    });

    // Маршруты для управления доступами
    Route::post('/access/audit', [AccessController::class, 'storeAuditAccess'])->name('access.audit.store');
    Route::post('/access/audit/update', [AccessController::class, 'updateAuditAccesses'])->name('access.audit.update');
    Route::delete('/access/{access}/audit/{audit}', [AccessController::class, 'destroyAuditAccess'])
        ->name('access.audit.destroy');

    // Маршруты для проведения аудита (вкладки B1-B14)
    Route::get('/audit/{audit}/process/{process}/tab/{tab}', [AuditController::class, 'process'])
        ->name('audit.process');

    // Маршрут для итоговой таблицы (B14)
    Route::get('/audit/{audit}/process/14', [AuditController::class, 'tab14'])
        ->name('audit.tab14');

    // Маршруты для печати
    Route::get('/audit/{audit}/process/{process}/tab/{tab}/print', [AuditController::class, 'printTab'])
        ->name('audit.print');
    Route::get('/audit/{audit}/process/{process}/tab/{tab}/list', [AuditController::class, 'printList'])
        ->name('audit.list');

    // Маршрут для сохранения оценок
    Route::post('/save-score', [AssessmentController::class, 'store'])
        ->name('assessment.store');

    // Маршруты для работы со свидетельствами
    Route::get('/organizations/{organization}/audits/{audit}/conduct/evidence/{norm?}', [AuditController::class, 'showEvidence'])
        ->name('audit.evidence');
    Route::post('/organizations/{organization}/audits/{audit}/conduct/evidence', [AuditController::class, 'storeEvidence'])
        ->name('audit.evidence.store');

    // Маршрут для загрузки оценок процессов
    Route::get('/api/process-scores/load', [AuditController::class, 'loadProcessScores'])
        ->name('process.scores.load');
});

// Маршруты для управления доступами (только для админов)
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::prefix('access')->group(function () {
        Route::get('/', [AccessController::class, 'index'])->name('access.index');
        Route::get('/create', [AccessController::class, 'create'])->name('access.create');
        Route::post('/', [AccessController::class, 'store'])->name('access.store');
        Route::get('/{access}/edit', [AccessController::class, 'edit'])->name('access.edit');
        Route::put('/{access}', [AccessController::class, 'update'])->name('access.update');
        Route::delete('/{access}', [AccessController::class, 'destroy'])->name('access.destroy');
        
        // Маршруты для доступов к аудитам
        Route::post('/audit', [AccessController::class, 'storeAuditAccess'])->name('access.audit.store');
        Route::post('/audit/update', [AccessController::class, 'updateAuditAccesses'])->name('access.audit.update');
        Route::delete('/{access}/audit/{audit}', [AccessController::class, 'destroyAuditAccess'])
            ->name('access.audit.destroy');
    });
});
