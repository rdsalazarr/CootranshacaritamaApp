<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Home\FrondController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Menu\ModuloController;
use App\Http\Controllers\Security\MantenimientoController;
use App\Http\Controllers\Admin\Menu\FuncionalidadController;

use App\Http\Controllers\Admin\Menu\RolController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\Notificar\InformacionCorreoController;
use App\Http\Controllers\Admin\DatosGeograficos\DepartamentoController;
use App\Http\Controllers\Admin\DatosGeograficos\MunicipioController;
use App\Http\Controllers\Admin\EmpresaController;
use App\Http\Controllers\Admin\Tipos\SaludoController;
use App\Http\Controllers\Admin\Tipos\DespedidaController;
use App\Http\Controllers\Admin\Series\SerieDocumentalController;
use App\Http\Controllers\Admin\Series\SubSerieDocumentalController;
use App\Http\Controllers\Admin\PersonaController;
use App\Http\Controllers\Admin\DependenciaController;
use App\Http\Controllers\Admin\ShowPersonaController;
use App\Http\Controllers\Util\DownloadFileController;
use App\Http\Controllers\Admin\ProducionDocumental\OficioController;
use App\Http\Controllers\Admin\ProducionDocumental\VisualizarTipoDocumentalController;

Route::get('/', [FrondController::class, 'index']);
Route::get('/login', [FrondController::class, 'index']);
Route::post('/login',[LoginController::class, 'login'])->name('login');
Route::match(array('GET', 'POST'),'/logout',[LoginController::class, 'logout'])->name('logout');

Route::get('/download/adjunto/{capeta}/{ruta}/{id}', [DownloadFileController::class, 'download'])->name('Download');

//'revalidate', verifySource
Route::middleware(['revalidate','auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/{id}', [DashboardController::class, 'index']);

    //Route::get('/admin/configurar/{id}', [DashboardController::class, 'index']);
    Route::get('/admin/produccion/documental/{id}', [DashboardController::class, 'index']);

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
        Route::post('/usuario/consultar/persona', [UsuarioController::class, 'consultar']);
        Route::post('/usuario/salve', [UsuarioController::class, 'salve']);
        Route::post('/usuario/destroy', [UsuarioController::class, 'destroy']);
        Route::get('/listar/datos/usuario', [UsuarioController::class, 'datos']);
        Route::get('/usuario/miPerfil', [UsuarioController::class, 'perfil']);
        Route::post('/usuario/updatePerfil', [UsuarioController::class, 'updatePerfil']);
        Route::post('/usuario/updatePassword', [UsuarioController::class, 'updatePassword']);

        Route::get('/informacionCorreo/list', [InformacionCorreoController::class, 'index']);
        Route::post('/informacionCorreo/salve', [InformacionCorreoController::class, 'salve']);
        Route::post('/informacionCorreo/destroy', [InformacionCorreoController::class, 'destroy']);

        Route::get('/departamento/list', [DepartamentoController::class, 'index']);
        Route::post('/departamento/salve', [DepartamentoController::class, 'salve']);

        Route::get('/municipio/list', [MunicipioController::class, 'index']);
        Route::get('/municipio/list/deptos', [MunicipioController::class, 'deptos']);
        Route::post('/municipio/salve', [MunicipioController::class, 'salve']);

        Route::get('/empresa/list', [EmpresaController::class, 'index']);
        Route::get('/empresa/list/datos', [EmpresaController::class, 'datos']);
        Route::post('/empresa/salve', [EmpresaController::class, 'salve']);

        Route::get('/tipoSaludo/list', [SaludoController::class, 'index']);
        Route::post('/tipoSaludo/salve', [SaludoController::class, 'salve']);
        Route::post('/tipoSaludo/destroy', [SaludoController::class, 'destroy']);

        Route::get('/tipoDespedida/list', [DespedidaController::class, 'index']);   
        Route::post('/tipoDespedida/salve', [DespedidaController::class, 'salve']);
        Route::post('/tipoDespedida/destroy', [DespedidaController::class, 'destroy']);

        Route::get('/serieDocumental/list', [SerieDocumentalController::class, 'index']);   
        Route::post('/serieDocumental/salve', [SerieDocumentalController::class, 'salve']);
        Route::post('/serieDocumental/destroy', [SerieDocumentalController::class, 'destroy']);

        Route::get('/subSerieDocumental/list', [SubSerieDocumentalController::class, 'index']);
        Route::get('/subSerieDocumental/listar/datos', [SubSerieDocumentalController::class, 'datos']);  
        Route::post('/subSerieDocumental/salve', [SubSerieDocumentalController::class, 'salve']);
        Route::post('/subSerieDocumental/destroy', [SubSerieDocumentalController::class, 'destroy']);

        Route::get('/persona/list', [PersonaController::class, 'index']); 
        Route::get('/persona/listar/datos', [PersonaController::class, 'datos']); 
        Route::post('/persona/salve', [PersonaController::class, 'salve']);
        Route::post('/persona/destroy', [PersonaController::class, 'destroy']);
        Route::post('/show/persona', [ShowPersonaController::class, 'index']);//No debe tener control de ruta
       
        Route::get('/dependencia/list', [DependenciaController::class, 'index']);
        Route::post('/dependencia/listar/datos', [DependenciaController::class, 'datos']);
        Route::post('/dependencia/salve', [DependenciaController::class, 'salve']);
        Route::post('/dependencia/destroy', [DependenciaController::class, 'destroy']);

        Route::prefix('/producion/documental')->group(function(){
            Route::get('/oficio/list', [OficioController::class, 'index']);
            Route::get('/oficio/consultar/area', [OficioController::class, 'area']);
            Route::post('/oficio/listar/datos', [OficioController::class, 'datos']);
            Route::post('/oficio/salve', [OficioController::class, 'salve']);


           
            Route::post('/visualizar/PDF', [VisualizarTipoDocumentalController::class, 'index']);

        });

    });
        
});


Route::get('/Eliminar', [MantenimientoController::class, 'clear']);
Route::get('/Mantenimiento', [MantenimientoController::class, 'down']);
Route::get('/Up/Mantenimiento', [MantenimientoController::class, 'up']);
Route::get('/Correo', [MantenimientoController::class, 'email']);
Route::get('/Generar/Pdf', [MantenimientoController::class, 'Pdf']);