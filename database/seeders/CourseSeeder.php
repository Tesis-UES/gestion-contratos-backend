<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         //UNIDAD DE SISTEMAS
         Course::create(['code'=>'IAI115','name'=>'Introducción a la Informática','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'PRN115','name'=>'Programacion I','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'PRN215','name'=>'Programacion II','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'PRN415','name'=>'Programación III','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'MSM115','name'=>'Manejo de Software para Microcomputadoras','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'ESD115','name'=>'Estructura de Datos','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'MEP115','name'=>'Métodos Probabilísticos','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'ANS115','name'=>'Analisis Numerico','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'HDP115','name'=>'Herramientas de Productividad','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'SYP115','name'=>'Sistemas y Procedimientos','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'MOP115','name'=>'Métodos de Optimización','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'ARC115','name'=>'Arquitectura de Computadoras','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'SIC115','name'=>'Sistemas Contables','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'TSI115','name'=>'Teoría de sistemas','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'MIP115','name'=>'Microprogramación','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'TAD115','name'=>'Teoría Administrativa','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'DSI115','name'=>'Diseño de Sistemas I','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'COS115','name'=>'Comunicaciones I','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'SIO115','name'=>'Sistemas Operativos','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'ANF115','name'=>'Análisis Financiero','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'DSI215','name'=>'Diseño de Sistemas II','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'RHU115','name'=>'Recursos Humanos','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'BAD115','name'=>'Bases de Datos','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'SGI115','name'=>'Sistemas de Información Gerencial','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'CPR115','name'=>'Consultoría Profesional','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'ACC115','name'=>'Administración de Centros de Cómputo','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'TPI115','name'=>'Técnicas de Programación para Internet','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'TOO15','name'=>'Tecnología Orientada a Objetos','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'PDM115','name'=>'Programación para Dispositivos Móviles','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'TDS15','name'=>'Técnicas de Simulación','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'COS215','name'=>'Comunicaciones IIs','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'EBB115','name'=>'Sistemas Embebidos I','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'SIF115','name'=>'Seguridad Informática','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'CET115','name'=>'Comercio Electrónico','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'AUS115','name'=>'Auditoría de Sistemas','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'IGF115','name'=>'Ingeniería de Software','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'IBD15','name'=>'Implementación de Bases de Datos','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'SGG115','name'=>'Sistemas de Información Geográficos','school_id'=>8,'study_plan_id'=>8]);
         Course::create(['code'=>'TDS115','name'=>'Trabajo de Graduacion','school_id'=>8,'study_plan_id'=>8]);

        

        //UNIDAD DE CIENCIAS BASICAS
        Course::create(['code'=>'MAT115','name'=>'Matematicas I','school_id'=>9,'study_plan_id'=>9]);
        Course::create(['code'=>'MAT215','name'=>'Matematicas II','school_id'=>9,'study_plan_id'=>9]);
        Course::create(['code'=>'MAT315','name'=>'Matematicas III','school_id'=>9,'study_plan_id'=>9]);
        Course::create(['code'=>'MAT415','name'=>'Matematicas IV','school_id'=>9,'study_plan_id'=>9]);
        Course::create(['code'=>'FIR115','name'=>'Fisica I','school_id'=>9,'study_plan_id'=>9]);
        Course::create(['code'=>'FIR215','name'=>'Fisica II','school_id'=>9,'study_plan_id'=>9]);
        Course::create(['code'=>'FIR315','name'=>'Fisica III','school_id'=>9,'study_plan_id'=>9]);
        Course::create(['code'=>'PYE115','name'=>'Probabilidad y Estadistica','school_id'=>9,'study_plan_id'=>9]);
        Course::create(['code'=>'MTE115','name'=>'Metodos Experimentales','school_id'=>9,'study_plan_id'=>9]);

       

    }
}
