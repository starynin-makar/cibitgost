use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\AuditController;

Route::post('/save-ratings', [RatingController::class, 'saveRatings']);
Route::get('/process-scores/load', [AuditController::class, 'loadProcessScores']);
Route::post('/process-scores/save', [AuditController::class, 'saveProcessScores']); 