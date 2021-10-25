<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;


class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Referencia: https://ssf.gob.sv/entidades-autorizadas/#
        Bank::create(['name' => 'Banco Agrícola, S.A.']);
        Bank::create(['name' => 'Banco Cuscatlán de El Salvador, S.A.']);
        Bank::create(['name' => 'Banco Davivienda Salvadoreño, S.A.']);
        Bank::create(['name' => 'Banco G&T Continental El Salvador, S.A.']);
        Bank::create(['name' => 'Banco Promérica, S.A.']);
        Bank::create(['name' => 'Scotiabank El Salvador, S.A.']);
        Bank::create(['name' => 'Banco de América Central, S.A.']);
        Bank::create(['name' => 'Banco Atlántida El Salvador, S.A.']);
        Bank::create(['name' => 'Banco Azteca El Salvador, S.A.']);
        Bank::create(['name' => 'Banco Industrial El Salvador, S.A.']);
        Bank::create(['name' => 'Banco Azul El Salvador, S.A.']);
        Bank::create(['name' => 'Citibank, N.A. Sucursal El Salvador']);
        Bank::create(['name' => 'Banco de Fomento Agropecuario']);
        Bank::create(['name' => 'Banco Hipotecario de El Salvador, S.A.']);
        Bank::create(['name' => 'Multi Inversiones Banco Cooperativo de los Trabajadores, Sociedad Cooperativa de R.L. de C.V.']);
        Bank::create(['name' => 'Banco de los Trabajadores Salvadoreños, S.C. de R.L. de C.V. – BTS R.L. de C.V.']);
        Bank::create(['name' => 'Banco Izalqueño de los Trabajadores, Sociedad Cooperativa de R.L. de C.V.']);
        Bank::create(['name' => 'Primer Banco de los Trabajadores, Sociedad Cooperativa de R.L. de C.V.']);
        Bank::create(['name' => 'Asociación Cooperativa de Ahorro y Crédito Visionaria de Responsabilidad Limitada o Banco Cooperativo Visionario de Responsabilidad Limitada. (ACCOVI de R.L o BANCOVI de R.L.)']);
        Bank::create(['name' => 'Sociedad de Ahorro y Crédito CREDICOMER, S.A.']);
        Bank::create(['name' => 'Sociedad de Ahorro y Crédito Apoyo Integral, S.A.']);
        Bank::create(['name' => 'Sociedad de Ahorro y Crédito Constelación, S.A.']);
        Bank::create(['name' => 'Sociedad de Ahorro y Crédito Multivalores, S.A.']);
    }
}
