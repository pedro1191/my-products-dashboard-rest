<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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

/**
 * Unauthenticated Routes
 */

Route::get('/', function () {
    return "Hello from " . config('app.name');
});

// Auth
Route::post('/auth/login', [AuthController::class, 'login']);

/**
 * Authenticated Routes
 */
Route::group([
    'middleware' => ['auth:api'],
], function () {
    // Auth
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::put('/auth/refresh', [AuthController::class, 'refresh']);
    Route::delete('/auth/logout', [AuthController::class, 'logout']);
});
