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
        $this->call(EscalafonSeeder::class);
        $this->call(FacultySeeder::class);
        $this->call(SchoolSeeder::class);
        $this->call(RolesPermissionsSeeder::class);
    }
}
