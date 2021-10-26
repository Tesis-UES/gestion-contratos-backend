<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
        /**
         * Seed the application's database.
         *
         * @return void
         */
        public function run()
        {
                $this->call(BankSeeder::class);
                $this->call(EscalafonSeeder::class);
                $this->call(FacultySeeder::class);
                $this->call(SchoolSeeder::class);
                $this->call(RolesPermissionsSeeder::class);
                $this->call(ContractTypeSeeder::class);
                $this->call(StudyPlanSeeder::class);
                $this->call(CourseSeeder::class);
                $this->call(GroupTypeSeeder::class);
                $this->call(EmployeeTypeSeeder::class);
                $this->call(FormatSeeder::class);
                $this->call(PersonSeeder::class);
                $this->call(PositionSeeder::class);
                $this->call(AcademicLoadSeeder::class);
        }
}
