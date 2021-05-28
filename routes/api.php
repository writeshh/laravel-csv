<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DataController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('data', DataController::class)->except('create', 'edit');

// Route::get('data', [DataController::class, 'index'])->name('data.index');
// Route::post('data', [DataController::class, 'store'])->name('data.store');
// Route::get('data/{id}', [DataController::class, 'show'])->name('data.show');
// Route::post('data/{id}', [DataController::class, 'update'])->name('data.update');
// Route::delete('data/{id}', [DataController::class, 'destroy'])->name('data.destroy');
