<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faculty;
use App\Models\FacultyAuthority;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Faculty::create(['name'=>'Facultad de Ingeniería y Arquitectura']);
        FacultyAuthority::create(['name'=>'PhD. Edgar Armando Peña Figueroa','position'=>'DECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>1]);
        FacultyAuthority::create(['name'=>'Ing. José María Sánchez Cornejo','position'=>'VICEDECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>1]);
        FacultyAuthority::create(['name'=>'Ing. Julio Alberto Portillo','position'=>'SECRETARIO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>1]);
        
        Faculty::create(['name'=>'Facultad de Ciencias Agronómicas']); 
        FacultyAuthority::create(['name'=>'Dr. Francisco Lara Ascencio','position'=>'DECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>2]);
        FacultyAuthority::create(['name'=>'Ing. Ludwing Leyton','position'=>'VICEDECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>2]);
        
        Faculty::create(['name'=>'Facultad de Ciencias Naturales y Matemática']);
        FacultyAuthority::create(['name'=>'Msc. Mauricio Lovo','position'=>'DECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>3]);
        FacultyAuthority::create(['name'=>'Msc. Zoila Guerrero','position'=>'VICEDECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>3]);

        Faculty::create(['name'=>'Facultad de Ciencias Económicas']);
        FacultyAuthority::create(['name'=>'Msc. Nixón Hernández','position'=>'DECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>4]);
        FacultyAuthority::create(['name'=>'Msc. Mario Crespín','position'=>'VICEDECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>4]);

        Faculty::create(['name'=>'Facultad de Odontología']);
        FacultyAuthority::create(['name'=>'Dr. Guillermo Aguirre','position'=>'DECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>5]);
        FacultyAuthority::create(['name'=>'Dr. Osmín Rivera','position'=>'VICEDECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>5]);

        Faculty::create(['name'=>'Facultad de Química y Farmacia']);
        FacultyAuthority::create(['name'=>'Licda. Reina Maribel Galdámez','position'=>'DECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>6]);
        FacultyAuthority::create(['name'=>'Licda. Nancy Zuleima González','position'=>'VICEDECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>6]);

        Faculty::create(['name'=>'Facultad de Jurisprudencia y Ciencias Sociales']);
        FacultyAuthority::create(['name'=>'Dra. Evelyn Farfán','position'=>'DECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>7]);
        FacultyAuthority::create(['name'=>'Dr. Edgardo Herrera Pacheco','position'=>'VICEDECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>7]);

        Faculty::create(['name'=>'Facultad de Medicina']);
        FacultyAuthority::create(['name'=>'Msc. Josefina Sibrián','position'=>'DECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>8]);
        FacultyAuthority::create(['name'=>'Dr. Saúl Díaz Peña','position'=>'VICEDECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>8]);

        Faculty::create(['name'=>'Facultad Multidisciplinaria Oriental']);
        FacultyAuthority::create(['name'=>'Lic. Cristóbal Ríos','position'=>'DECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>9]);
        FacultyAuthority::create(['name'=>'Lic. Óscar Villalobos','position'=>'VICEDECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>9]);

        Faculty::create(['name'=>'Facultad Multidisciplinaria de Occidente']);
        FacultyAuthority::create(['name'=>'Ing. Roberto Sigüenza','position'=>'DECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>10]);
        FacultyAuthority::create(['name'=>'Lic. Rina Bolaños de Zometa','position'=>'VICEDECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>10]);

        Faculty::create(['name'=>'Facultad Multidisciplinaria Paracentral']);
        FacultyAuthority::create(['name'=>'Ing. Roberto Antonio Díaz Flores','position'=>'DECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>11]);
        FacultyAuthority::create(['name'=>'Lic. Luis Alberto Mejía Orellana','position'=>'VICEDECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>11]);

        Faculty::create(['name'=>'Facultad de Ciencias y Humanidades']);
        FacultyAuthority::create(['name'=>'Lic. Óscar Wuilman Herrera Ramos','position'=>'DECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>12]);
        FacultyAuthority::create(['name'=>'Maestra Sandra Lorena Benavides de Serrano','position'=>'VICEDECANO','startPeriod'=>'2021-02-02','endPeriod'=>'2022-03-26','faculty_id'=>12]);

    }
}
