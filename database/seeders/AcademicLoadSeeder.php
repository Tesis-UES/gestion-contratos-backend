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
        

        //creacmos las cargas academicas para los ciclos anteriormente creados
        AcademicLoad::create(['semester_id'=>6,'school_id'=>8]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>1,'academic_load_id'=>1,'course_id'=>1,'people_id'=>1]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>1]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>1]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>2,'academic_load_id'=>1,'course_id'=>1,'people_id'=>2]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>2]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>2,'group_type_id'=>2,'academic_load_id'=>1,'course_id'=>1,'people_id'=>3]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>3]);

        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>1,'academic_load_id'=>1,'course_id'=>2,'people_id'=>1]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>4]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>4]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>2,'academic_load_id'=>1,'course_id'=>2,'people_id'=>2]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>5]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>2,'group_type_id'=>2,'academic_load_id'=>1,'course_id'=>2,'people_id'=>3]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>6]);
        
        AcademicLoad::create(['semester_id'=>5,'school_id'=>8]);

        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>1,'academic_load_id'=>2,'course_id'=>3,'people_id'=>1]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>7]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>7]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>2,'academic_load_id'=>2,'course_id'=>3,'people_id'=>2]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>8]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>2,'group_type_id'=>2,'academic_load_id'=>2,'course_id'=>3,'people_id'=>3]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>8]);

        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>1,'academic_load_id'=>2,'course_id'=>4,'people_id'=>1]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>10]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>10]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>2,'academic_load_id'=>2,'course_id'=>4,'people_id'=>2]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>11]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>2,'group_type_id'=>2,'academic_load_id'=>2,'course_id'=>4,'people_id'=>3]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>12]);





        AcademicLoad::create(['semester_id'=>4,'school_id'=>8]);

        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>1,'academic_load_id'=>3,'course_id'=>5,'people_id'=>1]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>13]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>13]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>2,'academic_load_id'=>3,'course_id'=>5,'people_id'=>2]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>14]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>2,'group_type_id'=>2,'academic_load_id'=>3,'course_id'=>5,'people_id'=>4]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>15]);

        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>1,'academic_load_id'=>3,'course_id'=>6,'people_id'=>2]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>16]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>16]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>2,'academic_load_id'=>3,'course_id'=>6,'people_id'=>1]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>17]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>2,'group_type_id'=>2,'academic_load_id'=>3,'course_id'=>6,'people_id'=>3]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>18]);


        AcademicLoad::create(['semester_id'=>3,'school_id'=>8]);

        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>1,'academic_load_id'=>4,'course_id'=>7,'people_id'=>1]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>19]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>19]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>2,'academic_load_id'=>4,'course_id'=>8,'people_id'=>1]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>20]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>2,'group_type_id'=>2,'academic_load_id'=>4,'course_id'=>9,'people_id'=>3]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>21]);

        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>1,'academic_load_id'=>4,'course_id'=>10,'people_id'=>1]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>22]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>22]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>2,'academic_load_id'=>4,'course_id'=>11,'people_id'=>2]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>23]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>2,'group_type_id'=>2,'academic_load_id'=>4,'course_id'=>12,'people_id'=>3]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>24]);

        AcademicLoad::create(['semester_id'=>2,'school_id'=>8]);

        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>1,'academic_load_id'=>5,'course_id'=>13,'people_id'=>1]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>25]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>25]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>2,'academic_load_id'=>5,'course_id'=>14,'people_id'=>2]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>26]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>2,'group_type_id'=>2,'academic_load_id'=>5,'course_id'=>15,'people_id'=>3]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>27]);

        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>1,'academic_load_id'=>5,'course_id'=>16,'people_id'=>1]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>28]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>28]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>2,'academic_load_id'=>5,'course_id'=>17,'people_id'=>2]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>29]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>2,'group_type_id'=>2,'academic_load_id'=>5,'course_id'=>18,'people_id'=>3]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>30]);   

        AcademicLoad::create(['semester_id'=>1,'school_id'=>8]);

        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>19,'people_id'=>1]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>31]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>31]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>2,'academic_load_id'=>6,'course_id'=>20,'people_id'=>2]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>32]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>2,'group_type_id'=>2,'academic_load_id'=>6,'course_id'=>21,'people_id'=>3]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>33]);

        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>22,'people_id'=>1]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>34]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>34]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>1,'group_type_id'=>2,'academic_load_id'=>6,'course_id'=>23,'people_id'=>4]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>35]);
        Group::create(['status'=>'DA','modality'=>'Presencial','number'=>2,'group_type_id'=>2,'academic_load_id'=>6,'course_id'=>24,'people_id'=>5]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>36]);   

        Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>12,'group_type_id'=>3,'academic_load_id'=>6,'course_id'=>19]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>37]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>37]);
        Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>13,'group_type_id'=>2,'academic_load_id'=>6,'course_id'=>20]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>38]);
        Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>24,'group_type_id'=>3,'academic_load_id'=>6,'course_id'=>21]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>39]);

        Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>15,'group_type_id'=>3,'academic_load_id'=>6,'course_id'=>22]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>40]);
        Schedule::create(['day'=>'Martes','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>40]);
        Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>13,'group_type_id'=>2,'academic_load_id'=>6,'course_id'=>23]);
        Schedule::create(['day'=>'Miercoles','start_hour'=>"8:00",'finish_hour'=>"9:45",'group_id'=>41]);
        Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>24,'group_type_id'=>3,'academic_load_id'=>6,'course_id'=>24]);
        Schedule::create(['day'=>'Jueves','start_hour'=>"9:00",'finish_hour'=>"9:45",'group_id'=>42]);   

        Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>25,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>23]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"9:00",'finish_hour'=>"9:45",'group_id'=>43]);
        Schedule::create(['day'=>'Miercoles','start_hour'=>"9:00",'finish_hour'=>"9:45",'group_id'=>43]);   
        Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>25,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>22]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"10:00",'finish_hour'=>"11:45",'group_id'=>44]);
        Schedule::create(['day'=>'Miercoles','start_hour'=>"12:00",'finish_hour'=>"1:45",'group_id'=>44]);   
        Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>26,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>21]);
        Schedule::create(['day'=>'Miercoles','start_hour'=>"10:00",'finish_hour'=>"11:45",'group_id'=>45]);
        Schedule::create(['day'=>'Viernes','start_hour'=>"12:00",'finish_hour'=>"14:45",'group_id'=>45]);   

        Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>123,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>12]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"9:00",'finish_hour'=>"9:45",'group_id'=>46]);
        Schedule::create(['day'=>'Miercoles','start_hour'=>"9:00",'finish_hour'=>"9:45",'group_id'=>46]);   
        Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>235,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>11]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"10:00",'finish_hour'=>"11:45",'group_id'=>47]);
        Schedule::create(['day'=>'Miercoles','start_hour'=>"12:00",'finish_hour'=>"13:45",'group_id'=>47]);   
        Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>265,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>13]);
        Schedule::create(['day'=>'Miercoles','start_hour'=>"10:00",'finish_hour'=>"11:45",'group_id'=>48]);
        Schedule::create(['day'=>'Viernes','start_hour'=>"12:00",'finish_hour'=>"14:45",'group_id'=>48]);   

        Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>3,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>1]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"9:00",'finish_hour'=>"9:45",'group_id'=>49]);
        Schedule::create(['day'=>'Miercoles','start_hour'=>"9:00",'finish_hour'=>"9:45",'group_id'=>49]);   
        Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>5,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>2]);
        Schedule::create(['day'=>'Jueves','start_hour'=>"10:00",'finish_hour'=>"11:45",'group_id'=>50]);
        Schedule::create(['day'=>'Viernes','start_hour'=>"12:00",'finish_hour'=>"13:45",'group_id'=>50]);   
        Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>5,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>3]);
        Schedule::create(['day'=>'Miercoles','start_hour'=>"10:00",'finish_hour'=>"11:45",'group_id'=>51]);
        Schedule::create(['day'=>'Jueves','start_hour'=>"12:00",'finish_hour'=>"14:45",'group_id'=>51]);   
        

        Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>55,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>4]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"15:00",'finish_hour'=>"17:45",'group_id'=>52]);
        Schedule::create(['day'=>'Miercoles','start_hour'=>"9:00",'finish_hour'=>"9:45",'group_id'=>52]);   
        Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>56,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>5]);
        Schedule::create(['day'=>'Jueves','start_hour'=>"13:00",'finish_hour'=>"15:45",'group_id'=>53]);
        Schedule::create(['day'=>'Viernes','start_hour'=>"16:00",'finish_hour'=>"20:45",'group_id'=>53]);   
        Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>57,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>6]);
        Schedule::create(['day'=>'Miercoles','start_hour'=>"10:00",'finish_hour'=>"11:45",'group_id'=>54]);
        Schedule::create(['day'=>'Jueves','start_hour'=>"12:00",'finish_hour'=>"14:45",'group_id'=>54]);   
    }

    }

