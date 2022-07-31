<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/agenda', [AgendaController::class, 'index'])->middleware(['auth', 'verified'])->name('agenda');

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('aparelhos',[AparelhoController::class, 'index'])->name('aparelhos');
    Route::get('atividades',[AtividadeController::class, 'index'])->name('atividades');
    Route::get('documentos',[DocumentoController::class, 'index'])->name('documentos');
    Route::get('financeiro',[FinanceiroController::class, 'index'])->name('financeiro');
    Route::get('pacientes', [PacienteController::class, 'index'])->name('pacientes');
    Route::get('planos',[PlanoController::class, 'index'])->name('planos');

    Route::get('adicionarAgenda',[AgendaController::class, 'create'])->name('adicionarAgenda');
    Route::post('adicionarAgenda',[AgendaController::class, 'store'])->name('gravarAgenda');
    Route::get('editarAgenda/{id}',[AgendaController::class, 'edit'])->name('editarAgenda');
    Route::post('atualizarAgenda',[AgendaController::class, 'edit'])->name('atualizarAgenda');
    Route::post('completarCompromisso/{id}',[AgendaController::class, 'completarCompromisso'])->name('completarCompromisso');
});

require __DIR__.'/auth.php';


// Route::group(['prefix' => '/'], function () {
//     Route::post('login', [AuthController::class, 'login']);
//     Route::post('register', [AuthController::class, 'register']);

//     Route::group(['middleware' => 'auth:sanctum'], function() {
//       Route::get('logout', [AuthController::class, 'logout']);
//       Route::get('user', [AuthController::class, 'user']);
//       Route::get('courses',[CourseController::class, 'getAllCourses'])->name('courses');
//     });
// });