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
            ['name' => 'Preparar material de apoyo para el laboratorio'],
            ['name' => 'Elaborar evlualaciones de labaratorio'],
            ['name' => 'Administrar evaluaciones de laboratorio'],
            ['name' => 'Calificar evaluaciones de laboratorio'],
            ['name' => 'Atender consultas de labortario'],
        ]);
        
        $profesorDiscusion = Position::create(['name' => 'Profesor de discusión']);
        $profesorDiscusion->activities()->createMany([
            ['name' => 'Impartir discusiones de la asignatura'],
            ['name' => 'Preparar material de apoyo de discucion '],
            ['name' => 'Elaborar evaluaciones de discusión'],
            ['name' => 'Administrar evaluaciones de discusión'],
            ['name' => 'Calificar evaluaciones de discusión'],
            ['name' => 'Atender consultas de discusión'],
        ]);        
        
        $profesor = Position::create(['name' => 'Profesor']);
        $profesor->activities()->createMany([
            ['name' => 'Impartir clases teoricas de la asignatura '],
            ['name' => 'Preparar material de apoyo de asignatura'],
            ['name' => 'Elaborar evaluaciones de asignatura'],
            ['name' => 'Administrar evaluaciones de asignatura'],
            ['name' => 'Calificar evaluaciones de asignatura'],
            ['name' => 'Atender consultas de asignatura'],
        ]);        
        
        $asesor = Position::create(['name' => 'Asesor de trabajo de graduacion']);
        $asesor->activities()->createMany([
            ['name' => 'Reunion semanal con estudiantes para planificar el contenido del trabajo de graduacion'],
            ['name' => 'Atender consultas de trabajo de graduacion'],
            ['name' => 'Revisar avances de trabajo de graduacion'],
            ['name' => 'Evaluar trabajo de graduacion'],
            ['name' => 'Calificar trabajo de graduacion'],
            ['name' => 'Programar defensas'],
            ['name' => 'Asistir como evaluador a otros trabajos de graduacion'],
        ]);
    }
}
