<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Home\FrondController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Menu\ModuloController;
use App\Http\Controllers\Admin\Menu\FuncionalidadController;
use App\Http\Controllers\Admin\Menu\RolController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\Notificar\InformacionCorreoController;

Route::get('/', [FrondController::class, 'index']);
Route::post('/login',[LoginController::class, 'login'])->name('login');
Route::match(array('GET', 'POST'),'/logout',[LoginController::class, 'logout'])->name('logout');

//'revalidate', verifySource
Route::middleware(['revalidate','auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/{id}', [DashboardController::class, 'index']);
    Route::get('reset', [DashboardController::class, 'reset']);
    Route::get('dataUsuario', [DashboardController::class, 'dataUsuario']);
    Route::post('updatePassword',[DashboardController::class, 'updatePassword']);
    
        
    Route::prefix('admin')->group(function(){
        Route::get('/modulo/list', [ModuloController::class, 'index']); //->middleware('security:Admin/Modulo/List')
        Route::post('/modulo/salve', [ModuloController::class, 'salve']);
        Route::post('/modulo/destroy', [ModuloController::class, 'destroy']);

        Route::get('/funcionalidad/list', [FuncionalidadController::class, 'index']); //->middleware('security:Admin/Funcionalidad')
        Route::get('/funcionalidad/listar/modulos', [FuncionalidadController::class, 'modulos']);
        Route::post('/funcionalidad/salve', [FuncionalidadController::class, 'salve']);
        Route::post('/funcionalidad/destroy', [FuncionalidadController::class, 'destroy']);

        Route::get('/rol/list', [RolController::class, 'index']); //->middleware('security:Admin/Rol')
        Route::post('/rol/listar/funcionalidad', [RolController::class, 'funcionalidades']);
        Route::post('/rol/salve', [RolController::class, 'salve']);
        Route::post('/rol/destroy', [RolController::class, 'destroy']);

        Route::get('/usuario/list', [UsuarioController::class, 'index']);
        Route::post('/usuario/salve', [UsuarioController::class, 'salve']);
        Route::post('/usuario/destroy', [UsuarioController::class, 'destroy']);
        Route::get('/usuario/miPerfil', [UsuarioController::class, 'perfil']);
        Route::post('/usuario/updatePerfil', [UsuarioController::class, 'updatePerfil']);
        Route::post('/usuario/updatePassword', [UsuarioController::class, 'updatePassword']);

        Route::get('/informacionCorreo/list', [InformacionCorreoController::class, 'index']);
        Route::post('/informacionCorreo/salve', [InformacionCorreoController::class, 'salve']);
        Route::post('/informacionCorreo/destroy', [InformacionCorreoController::class, 'destroy']);

    });
        
});