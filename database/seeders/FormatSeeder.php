<?php

namespace Database\Seeders;

use App\Models\Format;
use Illuminate\Database\Seeder;

class FormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Format::create([
           
            'name'        => 'Formato de Contrato de Tiempo Adicional Nacional  - Version 1.0',
            'type'        => 'Contrato de Tiempo Adicional',
            'file_url'     => 'TA-N.docx',
            'type_contract' => 'Nacional',
        ]);

        Format::create([
           
            'name'        => 'Formato de Contrato de Tiempo Adicional Internacional - Version 1.0',
            'type'        => 'Contrato de Tiempo Adicional',
            'file_url'     => 'TA-I.docx',
            'type_contract' => 'Internacional',
        ]);

        Format::create([
           
            'name'        => 'Formato de Contrato de Tiempo Integral Nacional- Version 1.0',
            'type'        => 'Contrato de Tiempo Integral',
            'file_url'     => 'TI-N.docx',
            'type_contract' => 'Nacional',
        ]);

        Format::create([
           
            'name'        => 'Formato de Contrato de Tiempo Integral Internacional - Version 1.0',
            'type'        => 'Contrato de Tiempo Integral',
            'file_url'     => 'TI-I.docx',
            'type_contract' => 'Internacional',
        ]);

        Format::create([
           
            'name'        => 'Formato de Contrato por Servicios Profesionales no Personales Nacional- Version 1.0',
            'type'        => 'Contrato por Servicios Profesionales no Personales',
            'file_url'     => 'SPNP-N.docx',
            'type_contract' => 'Nacional',
        ]);

        Format::create([
           
            'name'        => 'Formato de Contrato por Servicios Profesionales no Personales Internacional - Version 1.0',
            'type'        => 'Contrato por Servicios Profesionales no Personales',
            'file_url'     => 'SPNP-I.docx',
            'type_contract' => 'Internacional',
        ]);


       
    }
}
