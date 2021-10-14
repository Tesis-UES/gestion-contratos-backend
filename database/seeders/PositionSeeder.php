<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $tecnicoLab = Position::create(['name' => 'Técnico de laboratorio']);
        $tecnicoLab->activities()->createMany([
            ['name' => 'Programar y coordinar el uso de equipo de laboratorio'],
            ['name' => 'Asesorar el uso de equipo, insumos y materiales diversos'],
            ['name' => 'Monitorear e informar sobre la necesidad de insuos '],
            ['name' => 'Impartir cursos, talleres, charlas y ponencias'],
            ['name' => 'Promover y dar apoyo en estudios de investigacion tecnologica '],
        ]);

        $profesorLab = Position::create(['name' => 'Profesor de laboratorio']);
        $profesorLab->activities()->createMany([
            ['name' => 'Impartir laboratorio de la asignatura'],
            ['name' => 'Preparar material de apoyo'],
            ['name' => 'Elaborar evlualaciones'],
            ['name' => 'Administrar evaluaciones'],
            ['name' => 'Calificar evaluaciones'],
            ['name' => 'Atender consultas'],
        ]);
        
        $profesorDiscusion = Position::create(['name' => 'Profesor de discusión']);
        $profesorDiscusion->activities()->createMany([
            ['name' => 'Impartir discusiones de la asignatura'],
            ['name' => 'Preparar material de apoyo'],
            ['name' => 'Elaborar evlualaciones'],
            ['name' => 'Administrar evaluaciones'],
            ['name' => 'Calificar evaluaciones'],
            ['name' => 'Atender consultas'],
        ]);        
        
        $profesor = Position::create(['name' => 'Profesor']);
        $profesor->activities()->createMany([
            ['name' => 'Impartir clases teoricas de la asignatura '],
            ['name' => 'Preparar material de apoyo'],
            ['name' => 'Elaborar evlualaciones'],
            ['name' => 'Administrar evaluaciones'],
            ['name' => 'Calificar evaluaciones'],
            ['name' => 'Atender consultas'],
        ]);        
        
        $asesor = Position::create(['name' => 'Asesor de trabajo de graduacion']);
        $asesor->activities()->createMany([
            ['name' => 'Reunion semanal con estudiantes para planificar el contenido del trabajo de graduacion'],
            ['name' => 'Atender consultas'],
            ['name' => 'Revisar avances'],
            ['name' => 'Programar defensas'],
            ['name' => 'Asistir como evaluador a otros trabajos de graduacion'],
        ]);
    }
}
