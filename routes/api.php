<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AparelhoController;
use App\Http\Controllers\AtividadeController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PlanoController;
use App\Http\Controllers\ProntuarioController;
use App\Http\Controllers\UserController;

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

Route::group(['middleware' => 'auth:sanctum'], function() {
    
    
    // Route::get('atividades',[AtividadeController::class, 'index'])->name('atividades');
    // Route::get('documentos',[DocumentoController::class, 'index'])->name('documentos');
    // Route::get('financeiro',[FinanceiroController::class, 'index'])->name('financeiro');
    // Route::get('pacientes', [PacienteController::class, 'index'])->name('pacientes');
    // Route::get('planos',[PlanoController::class, 'index'])->name('planos');
});