use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PropertyImageController;

Route::prefix('admin')->group(function () {
    Route::post('/properties/{property}/images', [PropertyImageController::class, 'store']);
});
