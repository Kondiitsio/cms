<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\ImageController;

// Route::get('/', function () {
//     return Inertia::render('Welcome');
// })->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('pages', function () { return Inertia::render('Pages'); })->name('pages');
    Route::get('/pages/frontpage', [PageController::class, 'showPageBuilderBySlug'])->name('pages.builder.frontpage');
    Route::get('/pages/{slug}', [PageController::class, 'showPageBuilderBySlug'])->name('pages.builder');
});

Route::middleware('auth')->group(function () {
    Route::post('/api/pages', [PageController::class, 'store']);
    Route::get('/api/pages', [PageController::class, 'index']);
    Route::get('/api/pages/{page}', [PageController::class, 'show']);
    Route::put('/api/pages/{page}', [PageController::class, 'update']);
    Route::delete('/api/pages/{page}', [PageController::class, 'destroy']);
});

Route::middleware('auth')->group(function () {
    Route::post('/api/blocks/batch-update', [BlockController::class, 'batchUpdate']);
});

Route::get('/api/blocks/{pageId}', [BlockController::class, 'showByPageId']);

Route::get('/api/images/{id}', [ImageController::class, 'getImageById']);

Route::middleware('auth')->group(function () {
    Route::get('images', function () { return Inertia::render('Images'); })->name('images');
    Route::get('/api/images', [ImageController::class, 'showAll'])->name('image.showAll');
    Route::post('/api/images/upload', [ImageController::class, 'upload'])->name('images.upload');
    Route::put('/api/images/{id}', [ImageController::class, 'update'])->name('images.update');
    Route::delete('/api/images/{id}', [ImageController::class, 'destroy'])->name('images.destroy');
});


require __DIR__.'/auth.php';
require __DIR__.'/settings.php';

Route::get('/', [PageController::class, 'showFrontPage'])->name('home');
Route::get('/{slug}', [PageController::class, 'showBySlug'])->name('page');




