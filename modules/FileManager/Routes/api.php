<?php

use Illuminate\Support\Facades\Route;
use Modules\FileManager\Http\Controllers\FileManagerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('file')->as('file.')->group(function () {
    Route::prefix('v1')->as('v1.')->group(function () {
        Route::post('/', [FileManagerController::class, 'store'])->name('store')
            ->middleware(['auth:api']);
        Route::get('/slug/{slug}', [FileManagerController::class, 'showBySlug'])->name('show-by-slug');
        Route::get('/scope/{slug}', [FileManagerController::class, 'getByScope'])->name('get-by-scope');
        Route::get('/{file}', [FileManagerController::class, 'show'])->name('show');
        Route::get('/{file}/temp', [FileManagerController::class, 'showTemp'])->name('show-temp');

        Route::delete('/{file}', [FileManagerController::class, 'destroy'])->name('destroy')
            ->middleware(['auth:api']);
        Route::delete('/slug/{slug}', [FileManagerController::class, 'destroyBySlug'])->name('destroy-by-slug')
            ->middleware(['auth:api']);
    });
});
