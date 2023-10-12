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
        $this->call(TipoRelacionLaboralSeeder::class);
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
    }
}
