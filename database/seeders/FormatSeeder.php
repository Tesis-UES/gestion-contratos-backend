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
            'code'        => 'DJ-01',
            'name'        => 'Declaracion jurada',
            'is_template'  => true,
            'file_url'     => 'Declaracion-jurada.pdf',
        ]);

        Format::create([
            'code'        => 'SPNP',
            'name'        => 'Contrato ervicios profesionales no permanentes',
            'is_template'  => true,
            'file_url'     => 'SPNP.docx',
        ]);
    }
}
