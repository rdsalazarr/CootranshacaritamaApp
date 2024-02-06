<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Home\FrondController;
use App\Http\Controllers\Home\VerificarDocumentosController;
use App\Http\Controllers\Admin\Menu\RolController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Menu\ModuloController;
use App\Http\Controllers\Admin\Menu\FuncionalidadController;
use App\Http\Controllers\Security\MantenimientoController;
use App\Http\Controllers\Admin\Usuario\UsuarioController;
use App\Http\Controllers\Admin\Usuario\PerfilUsuarioController;
use App\Http\Controllers\Admin\Informacion\GeneralPdfController;
use App\Http\Controllers\Admin\Informacion\NotificacionCorreoController;
use App\Http\Controllers\Admin\Informacion\ConfiguracionCorreoController;
use App\Http\Controllers\Admin\DatosGeograficos\DepartamentoController;
use App\Http\Controllers\Admin\DatosGeograficos\MunicipioController;
use App\Http\Controllers\Admin\Empresa\EmpresaController;
use App\Http\Controllers\Admin\Agencia\AgenciaController;
use App\Http\Controllers\Admin\Tipos\SaludoController;
use App\Http\Controllers\Admin\Tipos\SancionController;
use App\Http\Controllers\Admin\Tipos\DespedidaController;
use App\Http\Controllers\Admin\Tipos\CargoLaboralController;
use App\Http\Controllers\Admin\Tipos\EntidadFinancieraController;
use App\Http\Controllers\Admin\Tipos\EstanteArchivadorController;
use App\Http\Controllers\Admin\Tipos\DocumentalController;
use App\Http\Controllers\Admin\Tipos\PersonaDocumentalController;
use App\Http\Controllers\Admin\Series\SerieDocumentalController;
use App\Http\Controllers\Admin\Series\SubSerieDocumentalController;
use App\Http\Controllers\Admin\Persona\PersonaController;
use App\Http\Controllers\Admin\Persona\DatosPersonaController;
use App\Http\Controllers\Admin\Festivo\FestivoController;
use App\Http\Controllers\Admin\Dependencia\DependenciaController;
use App\Http\Controllers\Util\DownloadFileController;
use App\Http\Controllers\Util\EliminarAchivosController;
use App\Http\Controllers\Admin\Exportar\RegistrosController;
use App\Http\Controllers\Admin\ProducionDocumental\ActaController; 
use App\Http\Controllers\Admin\ProducionDocumental\CertificadoController;
use App\Http\Controllers\Admin\ProducionDocumental\CircularController;
use App\Http\Controllers\Admin\ProducionDocumental\CitacionController;
use App\Http\Controllers\Admin\ProducionDocumental\ConstanciaController; 
use App\Http\Controllers\Admin\ProducionDocumental\OficioController; 
use App\Http\Controllers\Admin\ProducionDocumental\VisualizarDocumentosController;
use App\Http\Controllers\Admin\ProducionDocumental\FirmarDocumentosController;
use App\Http\Controllers\Admin\Radicacion\DocumentoEntranteController;
use App\Http\Controllers\Admin\Radicacion\ShowDocumentoEntranteController;
use App\Http\Controllers\Admin\Radicacion\AnularDocumentoEntranteController;
use App\Http\Controllers\Admin\Radicacion\BandejaRadicadoDocumentoEntranteController;
use App\Http\Controllers\Admin\Archivo\HistoricoController;
use App\Http\Controllers\Admin\Archivo\HistoricoShowController;
use App\Http\Controllers\Admin\Archivo\HistoricoConsultarController;
use App\Http\Controllers\Admin\Vehiculos\TipoVehiculoController;
use App\Http\Controllers\Admin\Vehiculos\TipoReferenciaController;
use App\Http\Controllers\Admin\Vehiculos\TipoCarroceriaController;
use App\Http\Controllers\Admin\Vehiculos\TipoModalidadController;
use App\Http\Controllers\Admin\Vehiculos\TipoMarcaController;
use App\Http\Controllers\Admin\Vehiculos\TipoColorController;
use App\Http\Controllers\Admin\Vehiculos\VehiculoController;
use App\Http\Controllers\Admin\Asociado\AsociadoController;
use App\Http\Controllers\Admin\Asociado\SancionarController;
use App\Http\Controllers\Admin\Asociado\DesvincularAsociadoController;
use App\Http\Controllers\Admin\Asociado\AsociadoInactivosController;
use App\Http\Controllers\Admin\Conductor\ConductorController;
use App\Http\Controllers\Admin\Vehiculos\SuspenderController;
use App\Http\Controllers\Admin\Vehiculos\AsignarVehiculoController;
use App\Http\Controllers\Admin\Vehiculos\DistribucionVehiculosController;

use App\Http\Controllers\Admin\Cartera\LineaCreditoController;
use App\Http\Controllers\Admin\Cartera\SolicitudCreditoController;
use App\Http\Controllers\Admin\Cartera\AprobarSolicitudCreditoController;
use App\Http\Controllers\Admin\Cartera\DesembolsarSolicitudCreditoController;
use App\Http\Controllers\Admin\Cartera\HistorialSolicitudCreditoController;
use App\Http\Controllers\Admin\Cartera\ShowSolicitudCreditoController;
use App\Http\Controllers\Admin\Cartera\GestionCobroCarteraController;

use App\Http\Controllers\Admin\Despacho\RutaController;
use App\Http\Controllers\Admin\Despacho\TiqueteController;
use App\Http\Controllers\Admin\Despacho\EncomiendaController;
use App\Http\Controllers\Admin\Despacho\PlanillaRutaController;
use App\Http\Controllers\Admin\Despacho\RecibirPlanillaRutaController;
use App\Http\Controllers\Admin\Despacho\ContratoServicioEspecialController;

use App\Http\Controllers\Admin\Caja\CuentaContableController;
use App\Http\Controllers\Admin\Caja\ProcesarMovimientoController;
use App\Http\Controllers\Admin\Caja\ConsignacionBancariaController;
use App\Http\Controllers\Admin\Caja\EntregarPagoCreditoController;
use App\Http\Controllers\Admin\Caja\CerrarMovimientoController;


Route::get('/', [FrondController::class, 'index']);
Route::get('/login', [FrondController::class, 'index']);
Route::post('/login',[LoginController::class, 'login'])->name('login');
Route::match(array('GET', 'POST'),'/logout',[LoginController::class, 'logout'])->name('logout');
Route::get('/verificar/documento/{id}', [VerificarDocumentosController::class, 'documental']);
Route::get('/verificar/contrato/servicio/especial/{id}', [VerificarDocumentosController::class, 'servicioEspecial']);
Route::post('/consultar/documento', [VerificarDocumentosController::class, 'consultarDocumento']);
Route::post('/consultar/contrato/servicio/especial', [VerificarDocumentosController::class, 'consultarServicioEspecial']);
Route::get('/download/certificado/{documento}/{ruta}', [DownloadFileController::class, 'certificado']);
Route::get('/download/digitalizados/{anyo}/{ruta}', [DownloadFileController::class, 'digitalizados']);
Route::get('/download/adjunto/radicado/{anyo}/{ruta}', [DownloadFileController::class, 'radicadoEntrante']);
Route::get('/download/adjunto/documental/{sigla}/{anyo}/{ruta}', [DownloadFileController::class, 'download']);
Route::get('/download/documentos/{sigla}/{anyo}/{ruta}', [VerificarDocumentosController::class, 'downloadDocumento']);//Decarga el documento con el QR
Route::get('/download/planilla/servicio/especial/{id}', [VerificarDocumentosController::class, 'downloadPlanillaSE']);//Decarga la planilla SE con el QR
Route::post('/admin/show/adjunto', [DownloadFileController::class, 'showAdjunto']);
Route::post('/admin/eliminar/archivo', [EliminarAchivosController::class, 'index']);
Route::post('/admin/eliminar/archivo/digitalizados', [EliminarAchivosController::class, 'digitalizados']);
Route::post('/admin/eliminar/archivo/radicado/entrante', [EliminarAchivosController::class, 'radicadoEntrante']);

Route::post('/admin/exportar/datos/cartera/vencida', [RegistrosController::class, 'exportarCarteraVencida']);
Route::post('/admin/exportar/datos/consulta/archivo/historico', [RegistrosController::class, 'exportarConsultaAH']);
Route::post('/admin/exportar/datos/movimiento/diarios', [RegistrosController::class, 'exportarMovimientoDiarios']);

// verifySource
Route::middleware(['revalidate','auth'])->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::get('admin/welcome', [DashboardController::class, 'welcome']);
    Route::get('admin/generarMenu', [DashboardController::class, 'generarMenu']);

    Route::get('/admin/miPerfil', [DashboardController::class, 'index']);
    Route::middleware(['preload'])->group(function (){//para recargar la pagina con f5
        Route::get('/admin/{id}', [DashboardController::class, 'index']);
        Route::get('/admin/caja/{id}', [DashboardController::class, 'index']);
        Route::get('/admin/despacho/{id}', [DashboardController::class, 'index']);
        Route::get('/admin/cartera/{id}', [DashboardController::class, 'index']);        
        Route::get('/admin/gestionar/{id}', [DashboardController::class, 'index']);
        Route::get('/admin/configurar/{id}', [DashboardController::class, 'index']);
        Route::get('/admin/archivo/historico/{id}', [DashboardController::class, 'index']);
        Route::get('/admin/produccion/documental/{id}', [DashboardController::class, 'index']);
        Route::get('/admin/radicacion/documento/{id}', [DashboardController::class, 'index']);
        Route::get('/admin/direccion/transporte/{id}', [DashboardController::class, 'index']);
    });

    Route::get('reset', [DashboardController::class, 'reset']);

    Route::prefix('admin')->group(function(){

        Route::get('/rol/list', [RolController::class, 'index'])->middleware('security:admin/configurar/menu');
        Route::post('/rol/listar/funcionalidad', [RolController::class, 'funcionalidades']);
        Route::post('/rol/salve', [RolController::class, 'salve']);
        Route::post('/rol/destroy', [RolController::class, 'destroy']);

        Route::get('/funcionalidad/list', [FuncionalidadController::class, 'index'])->middleware('security:admin/configurar/menu');
        Route::get('/funcionalidad/listar/modulos', [FuncionalidadController::class, 'modulos']);
        Route::post('/funcionalidad/salve', [FuncionalidadController::class, 'salve']);
        Route::post('/funcionalidad/destroy', [FuncionalidadController::class, 'destroy']);

        Route::get('/modulo/list', [ModuloController::class, 'index'])->middleware('security:admin/configurar/menu');
        Route::post('/modulo/salve', [ModuloController::class, 'salve']);
        Route::post('/modulo/destroy', [ModuloController::class, 'destroy']);
        
        Route::get('/informacionGeneralPdf/list', [GeneralPdfController::class, 'index'])->middleware('security:admin/configurar/GeneralPdf');
        Route::post('/informacionGeneralPdf/salve', [GeneralPdfController::class, 'salve']);
        Route::post('/informacionGeneralPdf/show/pdf', [GeneralPdfController::class, 'showPdf']);
        Route::post('/informacionGeneralPdf/destroy', [GeneralPdfController::class, 'destroy']);

        Route::get('/informacionCorreo/list', [NotificacionCorreoController::class, 'index'])->middleware('security:admin/configurar/notificarCorreo');
        Route::post('/informacionCorreo/salve', [NotificacionCorreoController::class, 'salve']);
        Route::post('/informacionCorreo/destroy', [NotificacionCorreoController::class, 'destroy']);

        Route::get('/configuracionCorreo/list', [ConfiguracionCorreoController::class, 'index'])->middleware('security:admin/configurar/notificarCorreo');
        Route::post('/configuracionCorreo/salve', [ConfiguracionCorreoController::class, 'salve']);
        Route::post('/configuracionCorreo/destroy', [ConfiguracionCorreoController::class, 'destroy']);        
        
        Route::get('/departamento/list', [DepartamentoController::class, 'index'])->middleware('security:admin/configurar/datosTerritorial');
        Route::post('/departamento/salve', [DepartamentoController::class, 'salve']);

        Route::get('/municipio/list', [MunicipioController::class, 'index'])->middleware('security:admin/configurar/datosTerritorial');
        Route::get('/municipio/list/deptos', [MunicipioController::class, 'deptos']);
        Route::post('/municipio/salve', [MunicipioController::class, 'salve']);

        Route::get('/empresa/list', [EmpresaController::class, 'index'])->middleware('security:admin/configurar/empresa');
        Route::get('/empresa/list/datos', [EmpresaController::class, 'datos']);
        Route::post('/empresa/salve', [EmpresaController::class, 'salve']); 

        Route::get('/tipoSaludo/list', [SaludoController::class, 'index'])->middleware('security:admin/gestionar/tipos');
        Route::post('/tipoSaludo/salve', [SaludoController::class, 'salve']);
        Route::post('/tipoSaludo/destroy', [SaludoController::class, 'destroy']);

        Route::get('/tipoDespedida/list', [DespedidaController::class, 'index'])->middleware('security:admin/gestionar/tipos');
        Route::post('/tipoDespedida/salve', [DespedidaController::class, 'salve']);
        Route::post('/tipoDespedida/destroy', [DespedidaController::class, 'destroy']);

        Route::get('/cargoLaboral/list', [CargoLaboralController::class, 'index'])->middleware('security:admin/gestionar/tipos');
        Route::post('/cargoLaboral/salve', [CargoLaboralController::class, 'salve']);
        Route::post('/cargoLaboral/destroy', [CargoLaboralController::class, 'destroy']);

        Route::get('/entidadFinanciera/list', [EntidadFinancieraController::class, 'index'])->middleware('security:admin/gestionar/tipos');
        Route::post('/entidadFinanciera/salve', [EntidadFinancieraController::class, 'salve']);
        Route::post('/entidadFinanciera/destroy', [EntidadFinancieraController::class, 'destroy']);

        Route::get('/tipoEstante/list', [EstanteArchivadorController::class, 'index'])->middleware('security:admin/gestionar/tipos');
        Route::post('/tipoEstante/salve', [EstanteArchivadorController::class, 'salve']);
        Route::post('/tipoEstante/destroy', [EstanteArchivadorController::class, 'destroy']);

        Route::get('/tipoDocumental/list', [DocumentalController::class, 'index'])->middleware('security:admin/gestionar/tipos');
        Route::post('/tipoDocumental/salve', [DocumentalController::class, 'salve']);
        Route::post('/tipoDocumental/destroy', [DocumentalController::class, 'destroy']);

        Route::get('/personaDocumental/list', [PersonaDocumentalController::class, 'index'])->middleware('security:admin/gestionar/tipos');
        Route::post('/personaDocumental/salve', [PersonaDocumentalController::class, 'salve']);
        Route::post('/personaDocumental/destroy', [PersonaDocumentalController::class, 'destroy']);

        Route::get('/tipoSancion/list', [SancionController::class, 'index'])->middleware('security:admin/gestionar/tipos');
        Route::post('/tipoSancion/salve', [SancionController::class, 'salve']);
        Route::post('/tipoSancion/destroy', [SancionController::class, 'destroy']);

        Route::get('/serieDocumental/list', [SerieDocumentalController::class, 'index'])->middleware('security:admin/gestionar/seriesDocumentales');
        Route::post('/serieDocumental/salve', [SerieDocumentalController::class, 'salve']);
        Route::post('/serieDocumental/destroy', [SerieDocumentalController::class, 'destroy']);

        Route::get('/subSerieDocumental/list', [SubSerieDocumentalController::class, 'index'])->middleware('security:admin/gestionar/seriesDocumentales');
        Route::get('/subSerieDocumental/listar/datos', [SubSerieDocumentalController::class, 'datos']);  
        Route::post('/subSerieDocumental/salve', [SubSerieDocumentalController::class, 'salve']);
        Route::post('/subSerieDocumental/destroy', [SubSerieDocumentalController::class, 'destroy']);
         
        Route::get('/dependencia/list', [DependenciaController::class, 'index'])->middleware('security:admin/gestionar/dependencia');
        Route::post('/dependencia/listar/datos', [DependenciaController::class, 'datos']);
        Route::post('/dependencia/salve', [DependenciaController::class, 'salve']);
        Route::post('/dependencia/destroy', [DependenciaController::class, 'destroy']);

        Route::get('/persona/list', [PersonaController::class, 'index'])->middleware('security:admin/gestionar/persona');       
        Route::post('/persona/salve', [PersonaController::class, 'salve']);
        Route::post('/persona/consultar/asignacion', [PersonaController::class, 'datos']);
        Route::post('/persona/procesar', [PersonaController::class, 'procesar']);
        Route::post('/persona/destroy', [PersonaController::class, 'destroy']);

        Route::post('/persona/listar/datos', [DatosPersonaController::class, 'index']); //No debe tener control de ruta
        Route::post('/show/persona', [DatosPersonaController::class, 'show']);

        Route::get('/usuario/list', [UsuarioController::class, 'index'])->middleware('security:admin/gestionar/usuario');
        Route::post('/usuario/consultar/persona', [UsuarioController::class, 'consultar']);
        Route::post('/usuario/list/datos', [UsuarioController::class, 'datos']);
        Route::post('/usuario/salve', [UsuarioController::class, 'salve']);
        Route::post('/usuario/destroy', [UsuarioController::class, 'destroy']);

        Route::get('/usuario/data', [PerfilUsuarioController::class, 'index']); //No debe tener control de ruta
        Route::get('/usuario/miPerfil', [PerfilUsuarioController::class, 'perfil']);
        Route::post('/usuario/updatePerfil', [PerfilUsuarioController::class, 'updatePerfil']);
        Route::post('/usuario/updatePassword', [PerfilUsuarioController::class, 'updatePassword']);

        Route::get('/festivo/list', [FestivoController::class, 'index'])->middleware('security:admin/gestionar/festivos');
        Route::post('/festivo/salve', [FestivoController::class, 'salve']);
        
        Route::get('/cuenta/contable/list', [CuentaContableController::class, 'index'])->middleware('security:admin/gestionar/cuentaContable');
        Route::post('/cuenta/contable/salve', [CuentaContableController::class, 'salve']);
        Route::post('/cuenta/contable/destroy', [CuentaContableController::class, 'destroy']);

        Route::get('/agencia/list', [AgenciaController::class, 'index'])->middleware('security:admin/gestionar/agencia');
        Route::get('/agencia/listar/datos', [AgenciaController::class, 'datos']);
        Route::post('/agencia/salve', [AgenciaController::class, 'salve']);
        Route::post('/agencia/destroy', [AgenciaController::class, 'destroy']);

        Route::get('/asociado/list', [AsociadoController::class, 'index'])->middleware('security:admin/gestionar/asociados');
        Route::post('/asociado/salve', [AsociadoController::class, 'salve']);
        Route::post('/asociado/destroy', [AsociadoController::class, 'destroy']);

        Route::get('/asociado/desvincular', [DesvincularAsociadoController::class, 'index'])->middleware('security:admin/gestionar/desvincularAsociado');       
        Route::post('/asociado/consultar', [DesvincularAsociadoController::class, 'consultar']);
        Route::post('/asociado/desvincular/salve', [DesvincularAsociadoController::class, 'desvincular']);

        Route::get('/asociado/sancionar/datos', [SancionarController::class, 'index'])->middleware('security:admin/gestionar/sancionarAsociado');
        Route::post('/asociado/sancionar/salve', [SancionarController::class, 'salve']);

        Route::get('/asociado/inactivos', [AsociadoInactivosController::class, 'index'])->middleware('security:admin/gestionar/asociadosInactivos');

        Route::prefix('/producion/documental')->group(function(){

            Route::post('/acta/list', [ActaController::class, 'index'])->middleware('security:admin/produccion/documental/acta');
            Route::get('/acta/consultar/area', [ActaController::class, 'area']);
            Route::post('/acta/listar/datos', [ActaController::class, 'datos']);
            Route::post('/acta/salve', [ActaController::class, 'salve']);
            Route::post('/acta/solicitar/firma', [ActaController::class, 'solicitarFirma']);
            Route::post('/verificar/sellado/acta', [ActaController::class, 'verificarSellado']);
            Route::post('/sellar/acta', [ActaController::class, 'sellar']);
            Route::post('/anular/acta', [ActaController::class, 'anular']);
            Route::post('/acta/visualizar/PDF', [ActaController::class, 'showPdf']);
            Route::post('/trazabilidad/acta', [ActaController::class, 'trazabilidad']);

            Route::post('/certificado/list', [CertificadoController::class, 'index'])->middleware('security:admin/produccion/documental/certificado');
            Route::get('/certificado/consultar/area', [CertificadoController::class, 'area']);
            Route::post('/certificado/listar/datos', [CertificadoController::class, 'datos']);
            Route::post('/certificado/salve', [CertificadoController::class, 'salve']);
            Route::post('/certificado/solicitar/firma', [CertificadoController::class, 'solicitarFirma']);
            Route::post('/verificar/sellado/certificado', [CertificadoController::class, 'verificarSellado']);
            Route::post('/sellar/certificado', [CertificadoController::class, 'sellar']);
            Route::post('/anular/certificado', [CertificadoController::class, 'anular']);
            Route::post('/certificado/visualizar/PDF', [CertificadoController::class, 'showPdf']);
            Route::post('/trazabilidad/certificado', [CertificadoController::class, 'trazabilidad']);

            Route::post('/circular/list', [CircularController::class, 'index'])->middleware('security:admin/produccion/documental/circular');
            Route::get('/circular/consultar/area', [CircularController::class, 'area']);
            Route::post('/circular/listar/datos', [CircularController::class, 'datos']);
            Route::post('/circular/salve', [CircularController::class, 'salve']);
            Route::post('/circular/solicitar/firma', [CircularController::class, 'solicitarFirma']);
            Route::post('/verificar/sellado/circular', [CircularController::class, 'verificarSellado']);
            Route::post('/sellar/circular', [CircularController::class, 'sellar']);
            Route::post('/anular/circular', [CircularController::class, 'anular']);
            Route::post('/circular/visualizar/PDF', [CircularController::class, 'showPdf']);
            Route::post('/trazabilidad/circular', [CircularController::class, 'trazabilidad']);

            Route::post('/citacion/list', [CitacionController::class, 'index'])->middleware('security:admin/produccion/documental/citacion');
            Route::get('/citacion/consultar/area', [CitacionController::class, 'area']);
            Route::post('/citacion/listar/datos', [CitacionController::class, 'datos']);
            Route::post('/citacion/salve', [CitacionController::class, 'salve']);
            Route::post('/citacion/solicitar/firma', [CitacionController::class, 'solicitarFirma']);
            Route::post('/verificar/sellado/citacion', [CitacionController::class, 'verificarSellado']);
            Route::post('/sellar/citacion', [CitacionController::class, 'sellar']);
            Route::post('/anular/citacion', [CitacionController::class, 'anular']);
            Route::post('/citacion/visualizar/PDF', [CitacionController::class, 'showPdf']);
            Route::post('/trazabilidad/citacion', [CitacionController::class, 'trazabilidad']);

            Route::post('/constancia/list', [ConstanciaController::class, 'index'])->middleware('security:admin/produccion/documental/constancia');
            Route::get('/constancia/consultar/area', [ConstanciaController::class, 'area']);
            Route::post('/constancia/listar/datos', [ConstanciaController::class, 'datos']);
            Route::post('/constancia/salve', [ConstanciaController::class, 'salve']);
            Route::post('/constancia/solicitar/firma', [ConstanciaController::class, 'solicitarFirma']);
            Route::post('/verificar/sellado/constancia', [ConstanciaController::class, 'verificarSellado']);
            Route::post('/sellar/constancia', [ConstanciaController::class, 'sellar']);
            Route::post('/anular/constancia', [ConstanciaController::class, 'anular']);
            Route::post('/constancia/visualizar/PDF', [ConstanciaController::class, 'showPdf']);
            Route::post('/trazabilidad/constancia', [ConstanciaController::class, 'trazabilidad']);

            Route::post('/oficio/list', [OficioController::class, 'index'])->middleware('security:admin/produccion/documental/oficio');
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
            Route::post('/list', [FirmarDocumentosController::class, 'index'])->middleware('security:admin/produccion/documental/firmar');
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
            Route::post('/entrante', [DocumentoEntranteController::class, 'index'])->middleware('security:admin/radicacion/documento/entrante');
            Route::post('/entrante/datos', [DocumentoEntranteController::class, 'datos']);
            Route::post('/entrante/consultar/persona', [DocumentoEntranteController::class, 'consultarPersona']);
            Route::post('/entrante/salve', [DocumentoEntranteController::class, 'salve']);
            Route::post('/entrante/imprimir', [DocumentoEntranteController::class, 'imprimir']);
            Route::post('/entrante/enviar', [DocumentoEntranteController::class, 'enviar']);
            Route::post('/entrante/consultar/radicado', [AnularDocumentoEntranteController::class, 'index'])->middleware('security:admin/radicacion/documento/anular');
            Route::post('/entrante/anular', [AnularDocumentoEntranteController::class, 'anular']);
            Route::post('/entrante/bandeja', [BandejaRadicadoDocumentoEntranteController::class, 'index'])->middleware('security:admin/radicacion/documento/bandeja');
            Route::post('/entrante/recibir', [BandejaRadicadoDocumentoEntranteController::class, 'recibir']);
            Route::post('/entrante/show', [ShowDocumentoEntranteController::class, 'index']); //No debe tener control de ruta
        });

        Route::prefix('/archivo/historico')->group(function(){
            Route::get('/gestionar/list', [HistoricoController::class, 'index'])->middleware('security:admin/archivo/historico/gestionar');
            Route::post('/obtener/datos', [HistoricoController::class, 'datos']);
            Route::post('/salve', [HistoricoController::class, 'salve']);
            Route::post('/show', [HistoricoShowController::class, 'index']);  //No debe tener control de ruta
            Route::get('/obtener/datos/consulta', [HistoricoConsultarController::class, 'index'])->middleware('security:admin/archivo/historico/consultar');
            Route::post('/consultar/datos', [HistoricoConsultarController::class, 'consultar']);
            Route::post('/consultar/expediente', [HistoricoConsultarController::class, 'expediente']);
            Route::post('/consultar/expediente/pdf', [HistoricoConsultarController::class, 'expedientePdf']);
        });

        Route::prefix('/direccion/transporte')->group(function(){
            Route::get('/tipo/list', [TipoVehiculoController::class, 'index'])->middleware('security:admin/direccion/transporte/tipos');
            Route::post('/tipo/salve', [TipoVehiculoController::class, 'salve']);
            Route::post('/tipo/destroy', [TipoVehiculoController::class, 'destroy']);
            Route::post('/distribucion/tipo/vehiculo', [TipoVehiculoController::class, 'datos']);
            Route::post('/tipo/distribucion/salve', [TipoVehiculoController::class, 'distribucion']);

            Route::get('/referencia/list', [TipoReferenciaController::class, 'index'])->middleware('security:admin/direccion/transporte/tipos');
            Route::post('/referencia/salve', [TipoReferenciaController::class, 'salve']);
            Route::post('/referencia/destroy', [TipoReferenciaController::class, 'destroy']);

            Route::get('/carroceria/list', [TipoCarroceriaController::class, 'index'])->middleware('security:admin/direccion/transporte/tipos');
            Route::post('/carroceria/salve', [TipoCarroceriaController::class, 'salve']);
            Route::post('/carroceria/destroy', [TipoCarroceriaController::class, 'destroy']);

            Route::get('/marca/list', [TipoMarcaController::class, 'index'])->middleware('security:admin/direccion/transporte/tipos');
            Route::post('/marca/salve', [TipoMarcaController::class, 'salve']);
            Route::post('/marca/destroy', [TipoMarcaController::class, 'destroy']);

            Route::get('/color/list', [TipoColorController::class, 'index'])->middleware('security:admin/direccion/transporte/tipos');
            Route::post('/color/salve', [TipoColorController::class, 'salve']);
            Route::post('/color/destroy', [TipoColorController::class, 'destroy']);

            Route::get('/modalidad/list', [TipoModalidadController::class, 'index'])->middleware('security:admin/direccion/transporte/tipos');
            Route::post('/modalidad/salve', [TipoModalidadController::class, 'salve']);
            Route::post('/modalidad/destroy', [TipoModalidadController::class, 'destroy']);

            Route::get('/vehiculo/list', [VehiculoController::class, 'index'])->middleware('security:admin/direccion/transporte/vehiculos');
            Route::post('/vehiculo/list/datos', [VehiculoController::class, 'datos']);
            Route::post('/vehiculo/salve', [VehiculoController::class, 'salve']);
            Route::post('/vehiculo/show', [VehiculoController::class, 'show']);
            Route::post('/vehiculo/destroy', [VehiculoController::class, 'destroy']);

            Route::get('/suspender/vehiculo/datos', [SuspenderController::class, 'index'])->middleware('security:admin/direccion/transporte/suspenderVehiculo');
            Route::post('/suspender/vehiculo/salve', [SuspenderController::class, 'salve']);

            Route::post('/conductor/list', [ConductorController::class, 'index'])->middleware('security:admin/direccion/transporte/conductores');
            Route::post('/conductor/salve', [ConductorController::class, 'salve']);
            Route::post('/conductor/destroy', [ConductorController::class, 'destroy']);
            Route::post('/conductor/suspender', [ConductorController::class, 'suspender']);
            Route::post('/conductor/activar/salve', [ConductorController::class, 'activar']);

            Route::get('/listar/vehiculos', [AsignarVehiculoController::class, 'index'])->middleware('security:admin/direccion/transporte/asignarVehiculo');
            Route::post('/consultar/informacion/vehiculo', [AsignarVehiculoController::class, 'consultarVehiculo']);
            Route::post('/listar/contratos/vehiculo', [AsignarVehiculoController::class, 'listarContratos']);
            Route::post('/asociados/imprimir/contrato', [AsignarVehiculoController::class, 'showPdf']);
            Route::post('/listar/conductores', [AsignarVehiculoController::class, 'listCondutores']);
            Route::post('/conductores/salve', [AsignarVehiculoController::class, 'salveConductor']);
            Route::post('/listar/soat', [AsignarVehiculoController::class, 'listSoat']);
            Route::post('/soat/salve', [AsignarVehiculoController::class, 'salveSoat']);
            Route::post('/listar/crt', [AsignarVehiculoController::class, 'listCrt']);
            Route::post('/crt/salve', [AsignarVehiculoController::class, 'salveCrt']);
            Route::post('/listar/poliza', [AsignarVehiculoController::class, 'listPoliza']);
            Route::post('/poliza/salve', [AsignarVehiculoController::class, 'salvePoliza']);
            Route::post('/listar/tarjeta/operacion', [AsignarVehiculoController::class, 'listTarjetaOperacion']);
            Route::post('/tarjeta/operacion/salve', [AsignarVehiculoController::class, 'salveTarjetaOperacion']);

            Route::get('/list/tipos/vehiculos', [DistribucionVehiculosController::class, 'index'])->middleware('security:admin/direccion/transporte/tipos');
            Route::post('/list/distribucion/vehiculo', [DistribucionVehiculosController::class, 'datos']);
            Route::post('/salve/distribucion/vehiculo', [DistribucionVehiculosController::class, 'salve']);//security:admin/direccion/transporte/distribucionVehiculos 
        });

        Route::prefix('/cartera')->group(function(){
            Route::get('/linea/credito/list', [LineaCreditoController::class, 'index'])->middleware('security:admin/cartera/lineaCredito');
            Route::post('/linea/credito/salve', [LineaCreditoController::class, 'salve']);
            Route::post('/linea/credito/destroy', [LineaCreditoController::class, 'destroy']);

            Route::get('/solicitud/credito/datos', [SolicitudCreditoController::class, 'index'])->middleware('security:admin/cartera/solicitud');
            Route::post('/consultar/datos/asociado', [SolicitudCreditoController::class, 'consultar']);
            Route::get('/solicitud/credito/tipo/documento', [SolicitudCreditoController::class, 'tipoDocumento']);
            Route::post('/consultar/datos/persona', [SolicitudCreditoController::class, 'persona']);
            Route::post('/registrar/solicitud/credito', [SolicitudCreditoController::class, 'salve']);
            Route::post('/simular/solicitud/credito', [SolicitudCreditoController::class, 'simular']);

            Route::get('/aprobar/solicitud/credito', [AprobarSolicitudCreditoController::class, 'index'])->middleware('security:admin/cartera/aprobacion');
            Route::get('/listar/estados/solicitud/credito', [AprobarSolicitudCreditoController::class, 'estados']);
            Route::post('/tomar/decision/solicitud/credito', [AprobarSolicitudCreditoController::class, 'salve']);
            
            Route::get('/desembolsar/solicitud/credito/datos', [DesembolsarSolicitudCreditoController::class, 'index'])->middleware('security:admin/cartera/desembolso');
            Route::post('/consultar/asociado', [DesembolsarSolicitudCreditoController::class, 'consultar']);
            Route::post('/desembolsar/solicitud/credito', [DesembolsarSolicitudCreditoController::class, 'salve']);

            Route::post('/show/solicitud/credito', [ShowSolicitudCreditoController::class, 'index']);//No debe tener control de ruta
            Route::post('/imprimir/documento/desembolso', [ShowSolicitudCreditoController::class, 'imprimir']);

            Route::get('/historial/solicitud/credito', [HistorialSolicitudCreditoController::class, 'index'])->middleware('security:admin/cartera/historial');

            Route::get('/gestionar/cobro/cartera', [GestionCobroCarteraController::class, 'index'])->middleware('security:admin/cartera/cobranza');
            Route::post('/show/colocacion', [GestionCobroCarteraController::class, 'showColocacion']);
            Route::post('/hacer/seguimiento/colocacion', [GestionCobroCarteraController::class, 'salveSeguimiento']);
        });

        Route::prefix('/despacho')->group(function(){
            Route::get('/ruta/list', [RutaController::class, 'index'])->middleware('security:admin/despacho/rutas');
            Route::post('/ruta/listar/datos', [RutaController::class, 'datos']);
            Route::post('/ruta/salve', [RutaController::class, 'salve']);
            Route::post('/ruta/destroy', [RutaController::class, 'destroy']);
            Route::post('/ruta/listar/datos/tiquete', [RutaController::class, 'datosTiquete']);
            Route::post('/ruta/salvar/datos/tiquete', [RutaController::class, 'tiquete']);

            Route::post('/planillas/list', [PlanillaRutaController::class, 'index'])->middleware('security:admin/despacho/planillas');
            Route::post('/planilla/listar/datos', [PlanillaRutaController::class, 'datos']);
            Route::post('/planilla/salve', [PlanillaRutaController::class, 'salve']);
            Route::post('/planilla/consultar/datos', [PlanillaRutaController::class, 'consultarDatos']);
            Route::post('/planilla/registrar/salida', [PlanillaRutaController::class, 'registrarSalida']);
            Route::post('/planilla/visualizar/PDF', [PlanillaRutaController::class, 'verPlanilla']);

            Route::post('/encomienda/list', [EncomiendaController::class, 'index'])->middleware('security:admin/despacho/encomiendas');
            Route::post('/encomienda/listar/datos', [EncomiendaController::class, 'datos']);
            Route::post('/encomienda/consultar/datos/persona', [EncomiendaController::class, 'consultarPersona']);
            Route::post('/encomienda/salve', [EncomiendaController::class, 'salve']);
            Route::post('/encomienda/show/general', [EncomiendaController::class, 'show']);
            Route::post('/encomienda/visualizar/PDF', [EncomiendaController::class, 'verFactura']);

            Route::post('/tiquete/list', [TiqueteController::class, 'index'])->middleware('security:admin/despacho/tiquetes');
            Route::post('/tiquete/listar/datos', [TiqueteController::class, 'datos']);
            Route::post('/tiquete/consultar/ventas/realizadas', [TiqueteController::class, 'consultarVenta']);
            Route::post('/tiquete/consultar/datos/persona', [TiqueteController::class, 'consultarPersona']);
            Route::post('/tiquete/salve', [TiqueteController::class, 'salve']);
            Route::post('/tiquete/show/general', [TiqueteController::class, 'show']);
            Route::post('/tiquete/visualizar/PDF', [TiqueteController::class, 'verFactura']);

            Route::get('/recibir/planilla/list', [RecibirPlanillaRutaController::class, 'index'])->middleware('security:admin/despacho/recibirPlanilla');
            Route::post('/recibir/planilla/salve', [RecibirPlanillaRutaController::class, 'salve']);
            Route::post('/consultar/persona/entregar/encomienda', [RecibirPlanillaRutaController::class, 'consultarPersona']);
            Route::post('/entregar/encomienda/salve', [RecibirPlanillaRutaController::class, 'salveEntregaEncomienda']);

            Route::post('/servicio/especial/list', [ContratoServicioEspecialController::class, 'index'])->middleware('security:admin/despacho/servicioEspecial');
            Route::post('/servicio/especial/listar/datos', [ContratoServicioEspecialController::class, 'datos']);
            Route::post('/servicio/especial/consultar/persona', [ContratoServicioEspecialController::class, 'consultarPersona']);
            Route::post('/servicio/especial/salve', [ContratoServicioEspecialController::class, 'salve']);
            Route::post('/servicio/especial/visualizar/PDF', [ContratoServicioEspecialController::class, 'verPlanilla']);
        });

        Route::prefix('/caja')->group(function(){
            Route::get('/procesar/movimiento', [ProcesarMovimientoController::class, 'index'])->middleware('security:admin/caja/procesar');
            Route::post('/abrir/dia', [ProcesarMovimientoController::class, 'abrirDia']);
            Route::get('/listar/vehiculos', [ProcesarMovimientoController::class, 'listVehiculos']);
            Route::post('/consultar/vehiculo', [ProcesarMovimientoController::class, 'consultarVehiculo']);
            Route::post('/registrar/mensualidad', [ProcesarMovimientoController::class, 'salveMensualidad']);

            Route::get('/listar/tipo/documento', [ProcesarMovimientoController::class, 'tipoDocumentos']);
            Route::post('/consultar/credito/asociado', [ProcesarMovimientoController::class, 'consultarCredito']);
            Route::post('/calcular/valor/cuota', [ProcesarMovimientoController::class, 'calcularCuota']);
            Route::post('/registrar/pago/cuota', [ProcesarMovimientoController::class, 'salvePagoCredito']);

            Route::post('/consultar/sancion/asociado', [ProcesarMovimientoController::class, 'consultarSancionAsociado']);
            Route::post('/registrar/sancion', [ProcesarMovimientoController::class, 'salveSancion']);

            Route::get('/listar/consignacion/bancaria', [ConsignacionBancariaController::class, 'index'])->middleware('security:admin/caja/consigarBanco');
            Route::get('/consultar/datos/consignacion/bancaria', [ConsignacionBancariaController::class, 'datosConsignacion']);
            Route::post('/registrar/consignacion/bancaria', [ConsignacionBancariaController::class, 'salveConsignacion']);

            Route::get('/pagar/credito/tipo/documento', [EntregarPagoCreditoController::class, 'index'])->middleware('security:admin/caja/entregarDesembolsoCredito');
            Route::post('/pagar/credito/consultar/persona', [EntregarPagoCreditoController::class, 'consultarPersona']);
            Route::post('/pagar/credito/entregar/efectivo', [EntregarPagoCreditoController::class, 'entregarEfectivo']);

            Route::get('/cerrar/movimiento', [CerrarMovimientoController::class, 'index'])->middleware('security:admin/caja/cerrar');
            Route::post('/cerrar/movimiento/salve', [CerrarMovimientoController::class, 'salve']);
        });

        Route::prefix('/antencion/usuario')->group(function(){
            Route::get('/list/registrados', [ProcesarMovimientoController::class, 'index'])->middleware('security:admin/antencion/usuario/gestionar');

        });

    });

}); 

Route::get('/Eliminar', [MantenimientoController::class, 'clear']);
Route::get('/Mantenimiento', [MantenimientoController::class, 'down']);
Route::get('/Up/Mantenimiento', [MantenimientoController::class, 'up']);
Route::get('/Correo', [MantenimientoController::class, 'email']);
Route::get('/Generar/Pdf', [MantenimientoController::class, 'Pdf']);
Route::get('/Contrato/Pdf', [MantenimientoController::class, 'contrato']);