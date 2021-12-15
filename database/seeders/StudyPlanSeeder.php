<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudyPlan;

class StudyPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   StudyPlan::create(['name' =>'Plan de estudios 1998 - ARQ-1998','school_id'=>'1']);
        StudyPlan::create(['name' =>'Plan de estudios 1998 - CIV-1998','school_id'=>'2']);
        StudyPlan::create(['name' =>'Plan de estudios 1998 - IND-1998','school_id'=>'3']);
        StudyPlan::create(['name' =>'Plan de estudios 1998 - MEC-1998','school_id'=>'4']);
        StudyPlan::create(['name' =>'Plan de estudios 1998 - EIE-1998','school_id'=>'5']);
        StudyPlan::create(['name' =>'Plan de estudios 1998 - EIQ-1998','school_id'=>'6']);
        StudyPlan::create(['name' =>'Plan de estudios 1998 - EIA-1998','school_id'=>'7']);
        StudyPlan::create(['name' =>'Plan de estudios 1998 - EISI-1998','school_id'=>'8']);
        StudyPlan::create(['name' =>'Plan de estudios 1998 - UCB-1998','school_id'=>'9']);
    }
}
