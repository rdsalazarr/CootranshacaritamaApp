<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(TipoIdentificacionSeeder::class);
        $this->call(CargoLaboralSeeder::class);
        $this->call(TipoDespedidaSeeder::class);
        $this->call(TipoDestinoSeeder::class);
        $this->call(TipoDocumentalSeeder::class);
        $this->call(TipoEstadoDocumentoSeeder::class);
        $this->call(TipoPersonaSeeder::class);
        $this->call(TipoSaludoSeeder::class);
        $this->call(SerieDocumentalSeeder::class);
        $this->call(SubSerieDocumentalSeeder::class);
        $this->call(TipoActaSeeder::class);
        $this->call(TipoMedioSeeder::class);
        $this->call(TipoPersonaDocumentalSeeder::class);
        $this->call(TipoTramiteSeeder::class);
        $this->call(PersonaSeeder::class);
        $this->call(UsuarioSeeder::class);
        $this->call(TipoEstadoDocumentoEntranteSeeder::class);
        $this->call(TipoEstanteArchivadorSeeder::class);
        $this->call(TipoCajaUbicacionSeeder::class);
        $this->call(TipoCarpetaUbicacionSeeder::class);
        $this->call(InformacionConfiguracionCorreoSeeder::class);
        $this->call(TipoVehiculoSeeder::class);
        $this->call(TipoReferenciaVehiculoSeeder::class);
        $this->call(TipoMarcaVehiculoSeeder::class);
        $this->call(TipoColorVehiculoSeeder::class);
        $this->call(TipoModalidadVehiculoSeeder::class);
        $this->call(TipoCarroceriaVehiculoSeeder::class);
        $this->call(TipoCombustibleVehiculoSeeder::class);
        $this->call(TipoEstadoVehiculoSeeder::class);
        $this->call(TipoEstadoAsociadoSeeder::class);
        $this->call(TipoEstadoSolicitudCreditoSeeder::class);
        $this->call(AgenciaSeeder::class);
        $this->call(TipoEstadoConductorSeeder::class);
        $this->call(TipoCategoriaLicenciaSeeder::class);        
        $this->call(TipoConductorSeeder::class);
    }
}
