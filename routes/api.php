<?php

use App\Http\Controllers\api\AuthController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'store']); 
Route::post('login', [AuthController::class, 'login'])->name('login'); 



// User dashboard (protected by user_api guard)
Route::prefix('user')->middleware('user.auth')->group(function () {
    Route::get('dashboard', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'Welcome to your dashboard',
            'user' => auth('user_api')->user()
        ]);
    });

    Route::post('logout',[AuthController::class,'logout'])->name('user.logout');;
});

