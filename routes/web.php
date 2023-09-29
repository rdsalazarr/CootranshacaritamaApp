<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Home\FrondController;
use App\Http\Controllers\Home\VerificarDocumentosController;
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
use App\Http\Controllers\Admin\Tipos\CargoLaboralController;
use App\Http\Controllers\Admin\Tipos\PersonaDocumentalController;
use App\Http\Controllers\Admin\Series\SerieDocumentalController;
use App\Http\Controllers\Admin\Series\SubSerieDocumentalController;
use App\Http\Controllers\Admin\PersonaController;
use App\Http\Controllers\Admin\DependenciaController;
use App\Http\Controllers\Admin\ShowPersonaController;
use App\Http\Controllers\Util\DownloadFileController;
use App\Http\Controllers\Util\EliminarAchivosController;

use App\Http\Controllers\Admin\ProducionDocumental\ActaController; 
use App\Http\Controllers\Admin\ProducionDocumental\CertificadoController;
use App\Http\Controllers\Admin\ProducionDocumental\CircularController;
use App\Http\Controllers\Admin\ProducionDocumental\CitacionController;
use App\Http\Controllers\Admin\ProducionDocumental\ConstanciaController; 
use App\Http\Controllers\Admin\ProducionDocumental\OficioController; 
use App\Http\Controllers\Admin\ProducionDocumental\VisualizarDocumentosController;
use App\Http\Controllers\Admin\ProducionDocumental\FirmarDocumentosController;

use App\Http\Controllers\Admin\Radicacion\DocumentoEntranteController;

Route::get('/', [FrondController::class, 'index']);
Route::get('/login', [FrondController::class, 'index']);
Route::post('/login',[LoginController::class, 'login'])->name('login');
Route::match(array('GET', 'POST'),'/logout',[LoginController::class, 'logout'])->name('logout');
Route::get('/verificar/documento/{id}', [VerificarDocumentosController::class, 'documental']);
Route::post('/consultar/documento', [VerificarDocumentosController::class, 'consultarDocumento']);
Route::get('/download/adjunto/radicado/{anyo}/{ruta}', [DownloadFileController::class, 'radicadoEntrante']);
Route::get('/download/adjunto/{sigla}/{anyo}/{ruta}', [DownloadFileController::class, 'download']);
Route::get('/download/documentos/{sigla}/{anyo}/{ruta}', [VerificarDocumentosController::class, 'downloadDocumento']);//Decarga el documento con el QR
Route::post('/admin/eliminar/archivo', [EliminarAchivosController::class, 'index']);
Route::post('/admin/eliminar/archivo/radicado/entrante', [EliminarAchivosController::class, 'radicadoEntrante']);



//'revalidate', verifySource
Route::middleware(['revalidate','auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/{id}', [DashboardController::class, 'index']);

    //para recargar la pagina con f5
    //Route::get('/admin/configurar/{id}', [DashboardController::class, 'index']);
    Route::get('/admin/produccion/documental/{id}', [DashboardController::class, 'index']);
    Route::get('/admin/radicacion/documento/{id}', [DashboardController::class, 'index']);
    Route::get('/admin/archivo/historico/{id}', [DashboardController::class, 'index']);

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
        
        Route::get('/cargoLaboral/list', [CargoLaboralController::class, 'index']);
        Route::post('/cargoLaboral/salve', [CargoLaboralController::class, 'salve']);
        Route::post('/cargoLaboral/destroy', [CargoLaboralController::class, 'destroy']);

        Route::get('/personaDocumental/list', [PersonaDocumentalController::class, 'index']);
        Route::post('/personaDocumental/salve', [PersonaDocumentalController::class, 'salve']);
        Route::post('/personaDocumental/destroy', [PersonaDocumentalController::class, 'destroy']);

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

            Route::post('/acta/list', [ActaController::class, 'index']);
            Route::get('/acta/consultar/area', [ActaController::class, 'area']);
            Route::post('/acta/listar/datos', [ActaController::class, 'datos']);
            Route::post('/acta/salve', [ActaController::class, 'salve']);
            Route::post('/acta/solicitar/firma', [ActaController::class, 'solicitarFirma']);
            Route::post('/verificar/sellado/acta', [ActaController::class, 'verificarSellado']);
            Route::post('/sellar/acta', [ActaController::class, 'sellar']);
            Route::post('/anular/acta', [ActaController::class, 'anular']);
            Route::post('/acta/visualizar/PDF', [ActaController::class, 'showPdf']);
            Route::post('/trazabilidad/acta', [ActaController::class, 'trazabilidad']);

            Route::post('/certificado/list', [CertificadoController::class, 'index']);
            Route::get('/certificado/consultar/area', [CertificadoController::class, 'area']);
            Route::post('/certificado/listar/datos', [CertificadoController::class, 'datos']);
            Route::post('/certificado/salve', [CertificadoController::class, 'salve']);
            Route::post('/certificado/solicitar/firma', [CertificadoController::class, 'solicitarFirma']);
            Route::post('/verificar/sellado/certificado', [CertificadoController::class, 'verificarSellado']);
            Route::post('/sellar/certificado', [CertificadoController::class, 'sellar']);
            Route::post('/anular/certificado', [CertificadoController::class, 'anular']);
            Route::post('/certificado/visualizar/PDF', [CertificadoController::class, 'showPdf']);
            Route::post('/trazabilidad/certificado', [CertificadoController::class, 'trazabilidad']);

            Route::post('/circular/list', [CircularController::class, 'index']);
            Route::get('/circular/consultar/area', [CircularController::class, 'area']);
            Route::post('/circular/listar/datos', [CircularController::class, 'datos']);
            Route::post('/circular/salve', [CircularController::class, 'salve']);
            Route::post('/circular/solicitar/firma', [CircularController::class, 'solicitarFirma']);
            Route::post('/verificar/sellado/circular', [CircularController::class, 'verificarSellado']);
            Route::post('/sellar/circular', [CircularController::class, 'sellar']);
            Route::post('/anular/circular', [CircularController::class, 'anular']);
            Route::post('/circular/visualizar/PDF', [CircularController::class, 'showPdf']);
            Route::post('/trazabilidad/circular', [CircularController::class, 'trazabilidad']);
            
            Route::post('/citacion/list', [CitacionController::class, 'index']);
            Route::get('/citacion/consultar/area', [CitacionController::class, 'area']);
            Route::post('/citacion/listar/datos', [CitacionController::class, 'datos']);
            Route::post('/citacion/salve', [CitacionController::class, 'salve']);
            Route::post('/citacion/solicitar/firma', [CitacionController::class, 'solicitarFirma']);
            Route::post('/verificar/sellado/citacion', [CitacionController::class, 'verificarSellado']);
            Route::post('/sellar/citacion', [CitacionController::class, 'sellar']);
            Route::post('/anular/citacion', [CitacionController::class, 'anular']);
            Route::post('/citacion/visualizar/PDF', [CitacionController::class, 'showPdf']);
            Route::post('/trazabilidad/citacion', [CitacionController::class, 'trazabilidad']);

            Route::post('/constancia/list', [ConstanciaController::class, 'index']);
            Route::get('/constancia/consultar/area', [ConstanciaController::class, 'area']);
            Route::post('/constancia/listar/datos', [ConstanciaController::class, 'datos']);
            Route::post('/constancia/salve', [ConstanciaController::class, 'salve']);
            Route::post('/constancia/solicitar/firma', [ConstanciaController::class, 'solicitarFirma']);
            Route::post('/verificar/sellado/constancia', [ConstanciaController::class, 'verificarSellado']);
            Route::post('/sellar/constancia', [ConstanciaController::class, 'sellar']);
            Route::post('/anular/constancia', [ConstanciaController::class, 'anular']);
            Route::post('/constancia/visualizar/PDF', [ConstanciaController::class, 'showPdf']);
            Route::post('/trazabilidad/constancia', [ConstanciaController::class, 'trazabilidad']);

            Route::post('/oficio/list', [OficioController::class, 'index']);
            Route::get('/oficio/consultar/area', [OficioController::class, 'area']);
            Route::post('/oficio/listar/datos', [OficioController::class, 'datos']);
            Route::post('/oficio/salve', [OficioController::class, 'salve']);
            Route::post('/oficio/solicitar/firma', [OficioController::class, 'solicitarFirma']);
            Route::post('/verificar/sellado/oficio', [OficioController::class, 'verificarSellado']);
            Route::post('/sellar/oficio', [OficioController::class, 'sellar']);
            Route::post('/anular/oficio', [OficioController::class, 'anular']);
            Route::post('/oficio/visualizar/PDF', [OficioController::class, 'showPdf']);
            Route::post('/trazabilidad/oficio', [OficioController::class, 'trazabilidad']);
        });

        Route::prefix('/firmar/documento')->group(function(){
            Route::post('/list', [FirmarDocumentosController::class, 'index']);
            Route::post('/solicitar/token', [FirmarDocumentosController::class, 'solicitarToken']);
            Route::post('/procesar', [FirmarDocumentosController::class, 'procesar']);

            Route::post('/editar/documento', [FirmarDocumentosController::class, 'editarDocumentos']);
            Route::post('/acta/salve', [FirmarDocumentosController::class, 'salvarActa']);
            Route::post('/certificado/salve', [FirmarDocumentosController::class, 'salvarCertificado']);
            Route::post('/cirular/salve', [FirmarDocumentosController::class, 'salvarCircular']);
            Route::post('/citacion/salve', [FirmarDocumentosController::class, 'salvarCitacion']);
            Route::post('/constancia/salve', [FirmarDocumentosController::class, 'salvarConstancia']);
            Route::post('/oficio/salve', [FirmarDocumentosController::class, 'salvarOficio']);
            Route::post('/visualizar/documento/PDF', [FirmarDocumentosController::class, 'showPdf']);
        });

        Route::prefix('/radicacion/documento')->group(function(){
            Route::post('/entrante', [DocumentoEntranteController::class, 'index']);
            Route::post('/entrante/datos', [DocumentoEntranteController::class, 'datos']);
            Route::post('/entrante/consultar/persona', [DocumentoEntranteController::class, 'consultarPersona']);
            Route::post('/entrante/salve', [DocumentoEntranteController::class, 'salve']);
            Route::post('/entrante/imprimir', [DocumentoEntranteController::class, 'imprimir']);

        });
    });
        
});


Route::get('/Eliminar', [MantenimientoController::class, 'clear']);
Route::get('/Mantenimiento', [MantenimientoController::class, 'down']);
Route::get('/Up/Mantenimiento', [MantenimientoController::class, 'up']);
Route::get('/Correo', [MantenimientoController::class, 'email']);
Route::get('/Generar/Pdf', [MantenimientoController::class, 'Pdf']);