<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Escalafon;

class EscalafonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ESCALAFON DE DOCENTES
        Escalafon::create(['code' => 'PU-I', 'name' => 'PROFESOR UNIVERSITARIO I', 'salary' => 1497.73 , 'hour_price'=>8.95]);
        Escalafon::create(['code' => 'PU-II', 'name' => 'PROFESOR UNIVERSITARIO II', 'salary' => 1843.36 , 'hour_price'=>11.01]);
        Escalafon::create(['code' => 'PU-III', 'name' => 'PROFESOR UNIVERSITARIO III', 'salary' => 2304.20 , 'hour_price'=>13.77]);

        //ESCALAFON DE PERSONAL ADMINISTRATIVO
        Escalafon::create(['code' => 'SG-I', 'name' => 'SERVICIOS GENERALES I', 'salary' => 674 , 'hour_price'=>0.00]);
        Escalafon::create(['code' => 'SG-II', 'name' => 'SERVICIOS GENERALES II', 'salary' => 974 , 'hour_price'=>0.00]);
        Escalafon::create(['code' => 'SG-III', 'name' => 'SERVICIOS GENERALES III', 'salary' => 906 , 'hour_price'=>0.00]);

        Escalafon::create(['code' => 'EC-I', 'name' => 'EMPLEADO CALIFICADO I', 'salary' => 906 , 'hour_price'=>0.00]);
        Escalafon::create(['code' => 'EC-II', 'name' => 'EMPLEADO CALIFICADO II', 'salary' => 959 , 'hour_price'=>0.00]);
        Escalafon::create(['code' => 'EC-III', 'name' => 'EMPLEADO CALIFICADO III', 'salary' => 1103 , 'hour_price'=>0.00]);

        Escalafon::create(['code' => 'AA-I', 'name' => 'ASISTENTE ADMINISTRATIVO I', 'salary' => 1103 , 'hour_price'=>0.00]);
        Escalafon::create(['code' => 'AA-II', 'name' => 'ASISTENTE ADMINISTRATIVO II', 'salary' => 1175 , 'hour_price'=>0.00]);
        Escalafon::create(['code' => 'AA-III', 'name' => 'ASISTENTE ADMINISTRATIVO III', 'salary' => 1248 , 'hour_price'=>0.00]);

        Escalafon::create(['code' => 'TC-I', 'name' => 'TECNICO I', 'salary' => 1248 , 'hour_price'=>0.00]);
        Escalafon::create(['code' => 'TC-II', 'name' => 'TECNICO II', 'salary' => 1325 , 'hour_price'=>0.00]);
        Escalafon::create(['code' => 'TC-III', 'name' => 'TECNICO III', 'salary' => 1413 , 'hour_price'=>0.00]);

        Escalafon::create(['code' => 'PUA-I', 'name' => 'PROFESIONAL UNIVERSITARIO ADMINISTRATIVO I', 'salary' => 1892 , 'hour_price'=>0.00]);
        Escalafon::create(['code' => 'PUA-II', 'name' => 'PROFESIONAL UNIVERSITARIO ADMINISTRATIVO II', 'salary' => 2111 , 'hour_price'=>0.00]);
        Escalafon::create(['code' => 'PUA-III', 'name' => 'PROFESIONAL UNIVERSITARIO ADMINISTRATIVO III', 'salary' => 2329 , 'hour_price'=>0.00]);
    }
}
