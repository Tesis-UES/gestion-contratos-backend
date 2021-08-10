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
    {
        StudyPlan::create(['name' =>'Plan de estudios 1998 - EISI-1998','school_id'=>'8']);
        StudyPlan::create(['name' =>'Plan de estudios 1998 - UCB-1998','school_id'=>'9']);
    }
}
