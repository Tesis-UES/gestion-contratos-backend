<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faculty;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Faculty::create(['name'=>'Facultad de Ciencias Agronómicas', 'dean' =>'Dr. Francisco Lara Ascencio', 'viceDean'=>'Ing. Ludwing Leyton' ]);
        Faculty::create(['name'=>'Facultad de Ingeniería y Arquitectura', 'dean' =>'Dr. Edgar Peña', 'viceDean'=>'Ing. José María Sánchez' ]);
        Faculty::create(['name'=>'Facultad de Ciencias Naturales y Matemática', 'dean' =>'Msc. Mauricio Lovo', 'viceDean'=>'Msc. Zoila Guerrero' ]);
        Faculty::create(['name'=>'Facultad de Ciencias Económicas', 'dean' =>'Msc. Nixón Hernández', 'viceDean'=>'Msc. Mario Crespín' ]);
        Faculty::create(['name'=>'Facultad de Odontología', 'dean' =>'Dr. Guillermo Aguirre', 'viceDean'=>'Dr. Osmín Rivera' ]);
        Faculty::create(['name'=>'Facultad de Química y Farmacia', 'dean' =>'Licda. Reina Maribel Galdámez', 'viceDean'=>'Licda. Nancy Zuleima González' ]);
        Faculty::create(['name'=>'Facultad de Jurisprudencia y Ciencias Sociales', 'dean' =>'Dra. Evelyn Farfán', 'viceDean'=>'Dr. Edgardo Herrera Pacheco' ]);
        Faculty::create(['name'=>'Facultad de Medicina', 'dean' =>'Msc. Josefina Sibrián', 'viceDean'=>'Dr. Saúl Díaz Peña' ]);
        Faculty::create(['name'=>'Facultad Multidisciplinaria Oriental', 'dean' =>'Lic. Cristóbal Ríos', 'viceDean'=>'Lic. Óscar Villalobos' ]);
        Faculty::create(['name'=>'Facultad Multidisciplinaria de Occidente', 'dean' =>'Ing. Roberto Sigüenza', 'viceDean'=>'Lic. Rina Bolaños de Zometa' ]);
        Faculty::create(['name'=>'Facultad Multidisciplinaria Paracentral', 'dean' =>'Ing. Roberto Antonio Díaz Flores', 'viceDean'=>'Lic. Luis Alberto Mejía Orellana' ]);
        Faculty::create(['name'=>'Facultad de Ciencias y Humanidades', 'dean' =>'Lic. Óscar Wuilman Herrera Ramos', 'viceDean'=>'Maestra Sandra Lorena Benavides de Serrano' ]);

    }
}
