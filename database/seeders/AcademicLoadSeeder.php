<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicLoad;
use App\Models\Semester;
use App\Models\Group;
use App\Models\Schedule;

class AcademicLoadSeeder extends Seeder
{
    
    public function run()
    {
        //Creamos un conjunto de ciclos unos no activos y uno solo activo
        Semester::Create(['name'=>'Ciclo 1-2019','start_date'=>'2019-02-12','end_date'=>'2019-07-12','status'=>false]);
        Semester::Create(['name'=>'Ciclo 2-2019','start_date'=>'2019-07-13','end_date'=>'2019-12-12','status'=>false]);
        Semester::Create(['name'=>'Ciclo 1-2020','start_date'=>'2020-02-12','end_date'=>'2020-07-12','status'=>false]);
        Semester::Create(['name'=>'Ciclo 2-2020','start_date'=>'2020-07-13','end_date'=>'2020-12-12','status'=>false]);
        Semester::Create(['name'=>'Ciclo 1-2021','start_date'=>'2021-02-12','end_date'=>'2021-07-12','status'=>false]);
        Semester::Create(['name'=>'Ciclo 2-2021','start_date'=>'2021-07-13','end_date'=>'2021-12-12','status'=>true]);

        //creacmos las cargas academicas para los ciclos anteriormente creados
        AcademicLoad::create(['semester_id'=>1,'school_id'=>8]);
        Group::create(['number'=>1,'group_type_id'=>1,'academic_load_id'=>1,'course_id'=>1]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>1]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>1]);
        Group::create(['number'=>1,'group_type_id'=>2,'academic_load_id'=>1,'course_id'=>1]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>2]);
        Group::create(['number'=>2,'group_type_id'=>2,'academic_load_id'=>1,'course_id'=>1]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>3]);

        Group::create(['number'=>1,'group_type_id'=>1,'academic_load_id'=>1,'course_id'=>2]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>4]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>4]);
        Group::create(['number'=>1,'group_type_id'=>2,'academic_load_id'=>1,'course_id'=>2]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>5]);
        Group::create(['number'=>2,'group_type_id'=>2,'academic_load_id'=>1,'course_id'=>2]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>6]);
        
        AcademicLoad::create(['semester_id'=>2,'school_id'=>8]);

        Group::create(['number'=>1,'group_type_id'=>1,'academic_load_id'=>2,'course_id'=>3]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>6]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>6]);
        Group::create(['number'=>1,'group_type_id'=>2,'academic_load_id'=>2,'course_id'=>3]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>7]);
        Group::create(['number'=>2,'group_type_id'=>2,'academic_load_id'=>2,'course_id'=>3]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>8]);

        Group::create(['number'=>1,'group_type_id'=>1,'academic_load_id'=>2,'course_id'=>4]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>9]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>9]);
        Group::create(['number'=>1,'group_type_id'=>2,'academic_load_id'=>2,'course_id'=>4]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>10]);
        Group::create(['number'=>2,'group_type_id'=>2,'academic_load_id'=>2,'course_id'=>4]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>11]);





        AcademicLoad::create(['semester_id'=>4,'school_id'=>8]);

        Group::create(['number'=>1,'group_type_id'=>1,'academic_load_id'=>3,'course_id'=>5]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>12]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>12]);
        Group::create(['number'=>1,'group_type_id'=>2,'academic_load_id'=>3,'course_id'=>5]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>13]);
        Group::create(['number'=>2,'group_type_id'=>2,'academic_load_id'=>3,'course_id'=>5]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>14]);

        Group::create(['number'=>1,'group_type_id'=>1,'academic_load_id'=>3,'course_id'=>6]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>15]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>15]);
        Group::create(['number'=>1,'group_type_id'=>2,'academic_load_id'=>3,'course_id'=>6]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>16]);
        Group::create(['number'=>2,'group_type_id'=>2,'academic_load_id'=>3,'course_id'=>6]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>16]);


        AcademicLoad::create(['semester_id'=>5,'school_id'=>8]);

        Group::create(['number'=>1,'group_type_id'=>1,'academic_load_id'=>4,'course_id'=>7]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>16]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>16]);
        Group::create(['number'=>1,'group_type_id'=>2,'academic_load_id'=>4,'course_id'=>8]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>17]);
        Group::create(['number'=>2,'group_type_id'=>2,'academic_load_id'=>4,'course_id'=>9]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>18]);

        Group::create(['number'=>1,'group_type_id'=>1,'academic_load_id'=>4,'course_id'=>10]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>19]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>19]);
        Group::create(['number'=>1,'group_type_id'=>2,'academic_load_id'=>4,'course_id'=>11]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>20]);
        Group::create(['number'=>2,'group_type_id'=>2,'academic_load_id'=>4,'course_id'=>12]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>20]);

        AcademicLoad::create(['semester_id'=>6,'school_id'=>8]);

        Group::create(['number'=>1,'group_type_id'=>1,'academic_load_id'=>5,'course_id'=>13]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>21]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>21]);
        Group::create(['number'=>1,'group_type_id'=>2,'academic_load_id'=>5,'course_id'=>14]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>22]);
        Group::create(['number'=>2,'group_type_id'=>2,'academic_load_id'=>5,'course_id'=>15]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>23]);

        Group::create(['number'=>1,'group_type_id'=>1,'academic_load_id'=>5,'course_id'=>16]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>24]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>24]);
        Group::create(['number'=>1,'group_type_id'=>2,'academic_load_id'=>5,'course_id'=>17]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>25]);
        Group::create(['number'=>2,'group_type_id'=>2,'academic_load_id'=>5,'course_id'=>18]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>26]);   

        AcademicLoad::create(['semester_id'=>3,'school_id'=>8]);

        Group::create(['number'=>1,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>19]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>27]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>27]);
        Group::create(['number'=>1,'group_type_id'=>2,'academic_load_id'=>6,'course_id'=>20]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>28]);
        Group::create(['number'=>2,'group_type_id'=>2,'academic_load_id'=>6,'course_id'=>21]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>28]);

        Group::create(['number'=>1,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>22]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>29]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>29]);
        Group::create(['number'=>1,'group_type_id'=>2,'academic_load_id'=>6,'course_id'=>23]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>30]);
        Group::create(['number'=>2,'group_type_id'=>2,'academic_load_id'=>6,'course_id'=>24]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>30]);   
    }
}
