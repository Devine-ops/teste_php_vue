<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\MovimentacaoController;
use Illuminate\Support\Facades\Route;

//rota publica
Route::post('/login',[LoginController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);
// Route::get('/users', [UserController::class, 'index']);
//rota restrita
Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('/users',[UserController::class, 'index']);
    Route::post('/logout',[LoginController::class, 'logout']);
    
    Route::middleware('auth:sanctum')->get('/categorias', [CategoriaController::class, 'index']);
    Route::post('/categorias', [CategoriaController::class, 'store']);

    Route::put('/movimentacoes/{id}', [MovimentacaoController::class, 'update']);
    Route::delete('/movimentacoes/{id}', [MovimentacaoController::class, 'destroy']);
    
    Route::middleware('auth:sanctum')->get('/movimentacoes', [MovimentacaoController::class, 'index']);
    Route::middleware('auth:sanctum')->post('/movimentacoes', [MovimentacaoController::class, 'store']);
});