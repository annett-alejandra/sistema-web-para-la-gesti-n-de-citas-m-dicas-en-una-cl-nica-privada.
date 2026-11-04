<?php

use App\Http\Controllers\Paquetes\P1_SeguridadAdministracion\CU02_CerrarSesion\LogoutController;
use App\Http\Controllers\Paquetes\P1_SeguridadAdministracion\CU03_GestionarUsuariosRoles\UsuariosController;
use App\Http\Controllers\Paquetes\P1_SeguridadAdministracion\CU04_GestionarEspecialidadesMedicas\EspecialidadController;
use App\Http\Controllers\Paquetes\P2_PersonalMedico\CU05_GestionarMedicos\MedicosController;
use App\Http\Controllers\Paquetes\P2_PersonalMedico\CU06_GestionarHorariosAtencion\HorariosController;
use App\Http\Controllers\Paquetes\P3_GestionPacientes\CU07_GestionarPacientes\PacientesController;
use App\Http\Controllers\Paquetes\P3_GestionPacientes\CU09_RegistrarCuentaPaciente\RegistroPacienteController;
use App\Http\Controllers\Paquetes\P4_GestionCitas\CU10_ConsultarDisponibilidadMedica\DisponibilidadController;
use App\Http\Controllers\Paquetes\P4_GestionCitas\CU11_ProgramarCitaMedica\CitaMedicaController;
use Illuminate\Support\Facades\Route;

Route::pattern('id', '[0-9]+');

Route::view('/', 'welcome')->name('inicio');
Route::view('/iniciar-sesion', 'Paquetes.P1_SeguridadAdministracion.CU01_IniciarSesion.index')->middleware('guest')->name('login');

Route::middleware('guest')->group(function () {
    Route::view('/registro-paciente', 'Paquetes.P3_GestionPacientes.CU09_RegistrarCuentaPaciente.index')->name('registro.index');
    Route::post('/registro-paciente', [RegistroPacienteController::class, 'register'])->middleware('throttle:5,1')->name('registro.store');
});

Route::middleware(['auth', 'cache.headers:private;no_store'])->group(function () {
    Route::post('/cerrar-sesion', LogoutController::class)->name('logout');
    Route::view('/panel', 'Paquetes.P1_SeguridadAdministracion.CU02_CerrarSesion.index')->middleware('can:portal')->name('panel');

    Route::middleware('can:administracion')->group(function () {
        Route::resource('usuarios', UsuariosController::class)->only(['index', 'store', 'update'])->parameters(['usuarios' => 'id']);
        Route::patch('/usuarios/{id}/estado', [UsuariosController::class, 'toggleEstado'])->name('usuarios.estado');
        Route::resource('especialidades', EspecialidadController::class)->only(['index', 'store', 'update'])->parameters(['especialidades' => 'id']);
        Route::patch('/especialidades/{id}/estado', [EspecialidadController::class, 'toggleEstado'])->name('especialidades.estado');
    });

    Route::middleware('can:recepcion')->group(function () {
        Route::resource('medicos', MedicosController::class)->only(['index', 'store', 'update'])->parameters(['medicos' => 'id']);
        Route::patch('/medicos/{id}/estado', [MedicosController::class, 'toggleEstado'])->name('medicos.estado');
        Route::resource('pacientes', PacientesController::class)->only(['index', 'store', 'update'])->parameters(['pacientes' => 'id']);
        Route::patch('/pacientes/{id}/estado', [PacientesController::class, 'toggleEstado'])->name('pacientes.estado');
        Route::post('/horarios', [HorariosController::class, 'store'])->name('horarios.store');
        Route::delete('/horarios/{id}', [HorariosController::class, 'destroy'])->name('horarios.destroy');
    });

    Route::get('/horarios', [HorariosController::class, 'index'])->middleware('can:horarios')->name('horarios.index');
    Route::get('/disponibilidad', [DisponibilidadController::class, 'consultar'])->middleware('can:portal')->name('disponibilidad.index');
    Route::get('/citas', [CitaMedicaController::class, 'index'])->middleware('can:portal')->name('citas.index');
    Route::get('/citas/{codigo}', [CitaMedicaController::class, 'show'])->whereUuid('codigo')->middleware('can:portal')->name('citas.show');
    Route::post('/citas', [CitaMedicaController::class, 'store'])->middleware('can:reservar')->name('citas.store');
});
