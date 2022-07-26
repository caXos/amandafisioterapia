<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\CompromissoController;
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

Route::get('/agenda', [CompromissoController::class, 'index'])->middleware(['auth', 'verified'])->name('agenda');

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('aparelhos',[AparelhoController::class, 'index'])->name('aparelhos');
    Route::get('atividades',[AtividadeController::class, 'index'])->name('atividades');
    Route::get('documentos',[DocumentoController::class, 'index'])->name('documentos');
    Route::get('financeiro',[FinanceiroController::class, 'index'])->name('financeiro');
    Route::get('pacientes', [PacienteController::class, 'index'])->name('pacientes');
    Route::get('planos',[PlanoController::class, 'index'])->name('planos');

    Route::get('historico',[CompromissoController::class, 'historico'])->name('historico');

    Route::get('adicionarCompromisso',[CompromissoController::class, 'create'])->name('adicionarCompromisso');
    Route::post('adicionarCompromisso',[CompromissoController::class, 'store'])->name('gravarCompromisso');
    Route::get('editarCompromisso/{id}',[CompromissoController::class, 'edit'])->name('editarCompromisso');
    Route::post('atualizarCompromisso',[CompromissoController::class, 'update'])->name('atualizarCompromisso');
    // Route::get('completarCompromisso/{id}',[CompromissoController::class, 'completarCompromisso'])->name('completarCompromisso');
    Route::delete('completarCompromisso/{id}',[CompromissoController::class, 'completarCompromisso'])->name('completarCompromisso');
    Route::delete('completarCompromisso/{id}/{novoId}',[CompromissoController::class, 'completarCompromissoComRetorno'])->name('completarCompromissoComRetorno');
    // Route::get('deletarCompromisso/{id}',[CompromissoController::class, 'deletarCompromisso'])->name('deletarCompromisso');
    Route::delete('deletarCompromisso/{id}',[CompromissoController::class, 'deletarCompromisso'])->name('deletarCompromisso');
    Route::get('notificarCompromisso/{id}',[CompromissoController::class, 'notificarCompromisso'])->name('notificarCompromisso');
    Route::post('faltar/{id}',[CompromissoController::class, 'faltarCompromisso'])->name('faltarCompromisso');

    Route::get('adicionarFinanceiro',[FinanceiroController::class, 'create'])->name('adicionarFinanceiro');
    Route::post('adicionarFinanceiro',[FinanceiroController::class, 'store'])->name('gravarFinanceiro');
    Route::get('editarFinanceiro/{id}',[FinanceiroController::class, 'edit'])->name('editarFinanceiro');
    Route::post('editarFinanceiro/{id}',[FinanceiroController::class, 'update'])->name('editarFinanceiro');
    // Route::get('deletarFinanceiro/{id}',[FinanceiroController::class, 'deletarFinanceiro'])->name('deletarFinanceiro');
    Route::delete('deletarFinanceiro/{id}',[FinanceiroController::class, 'deletarFinanceiro'])->name('deletarFinanceiro');

    Route::get('adicionarPaciente',[PacienteController::class, 'create'])->name('adicionarPaciente');
    Route::post('prepararGravarPaciente',[PacienteController::class, 'prepareStore'])->name('prepararGravarPaciente');
    Route::post('adicionarPaciente',[PacienteController::class, 'store'])->name('gravarPaciente');
    Route::get('editarPaciente/{id}',[PacienteController::class, 'edit'])->name('editarPaciente');
    Route::post('editarPaciente/{id}',[PacienteController::class, 'update'])->name('editarPaciente');
    Route::delete('deletarPaciente/{id}',[PacienteController::class, 'deletarPaciente'])->name('deletarPaciente');
    
    Route::get('prontuariosPaciente/{id}',[ProntuarioController::class, 'index'])->name('prontuariosPaciente');
    Route::get('adicionarProntuario/{paciente_id}',[ProntuarioController::class, 'create'])->name('adicionarProntuario');
    Route::post('adicionarProntuario/{paciente_id}',[ProntuarioController::class, 'store'])->name('gravarProntuario');
    Route::get('editarProntuario/{paciente_id}/{id}',[ProntuarioController::class, 'edit'])->name('editarProntuario');
    Route::post('editarProntuario/{paciente_id}/{id}',[ProntuarioController::class, 'update'])->name('editarProntuario');
    Route::get('deletarProntuario/{paciente_id}/{id}',[ProntuarioController::class, 'deletarProntuario'])->name('deletarProntuario');

    Route::get('debug', [CompromissoController::class, 'debug'])->name('debug');
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