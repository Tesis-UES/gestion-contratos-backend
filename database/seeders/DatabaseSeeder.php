<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HiringRequest;
use App\Models\User;
use App\Models\PersonValidation;
use App\Models\{Person,EmployeeType,Semester,Activity,Employee,StaySchedule,Group,Schedule};
use App\Http\Controllers\PersonController;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

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
                $this->call(StatusSeeder::class);
                $this->call(ContractStatusSeeder::class);
                $this->call(HrSeeder::class);

                Group::factory(150)->create()->each(function ($group) {
                   
                    if ($group->group_type_id == 1 && $group->modality == 'En Linea') {
                        $group->schedule()->saveMany(Schedule::factory(5)->make());
                    } else {
                        $group->schedule()->saveMany(Schedule::factory(2)->make());
                    }
                });

                HiringRequest::factory()->count(100)->create()->each(function ($hiringRequest) {
                        $hiringRequest->status()->attach(['status_id' => '1'], ['comments' => 'Registro de solicitud']);
                        $hiringRequest->status()->attach(['status_id' => '2'], ['comments' => 'Llenado de datos de solicitud de contratación']);
                });
               
                //Candidatos que no son trabajadores de la UES XD
                User::factory()->count(50)->create()->each(function ($user) {
                        $user->assignRole('Candidato');
                        $user->person()->save(Person::factory()->make());
                        $personValidation = new PersonValidation(['person_id' => $user->person->id]);
                        $personValidation->save();
                        $task = new PersonController;
                        $r = $task->mergePersonalDoc($user->person->id);
                });

                //Trabajadores de la UES
              
                User::factory()->count(50)->create()->each(function ($user) {
                       
                        $employeeTypes = EmployeeType::all();
                        $semester = Semester::firstWhere('status', 1);
                        //detalles de horario
                        $details = [
                            [
                                "day" => "Lunes",
                                "start_time" => "07:00",
                                "finish_time" => "12:00"
                            ],
                            [
                                "day" => "Lunes",
                                "start_time" => "13:00",
                                "finish_time" => "16:00"
                            ],
                            [
                                "day" => "Martes",
                                "start_time" => "07:00",
                                "finish_time" => "12:00"
                            ],
                            [
                                "day" => "Martes",
                                "start_time" => "13:00",
                                "finish_time" => "16:00"
                            ],
                            [
                                "day" => "Miercoles",
                                "start_time" => "07:00",
                                "finish_time" => "12:00"
                            ],
                            [
                                "day" => "Miercoles",
                                "start_time" => "13:00",
                                "finish_time" => "16:00"
                            ],
                            [
                                "day" => "Jueves",
                                "start_time" => "07:00",
                                "finish_time" => "12:00"
                            ],
                            [
                                "day" => "Jueves",
                                "start_time" => "13:00",
                                "finish_time" => "16:00"
                            ],
                            [
                                "day" => "Viernes",
                                "start_time" => "07:00",
                                "finish_time" => "12:00"
                            ],
                            [
                                "day" => "Viernes",
                                "start_time" => "13:00",
                                "finish_time" => "16:00"
                            ]
                        ];
                        $activities = [
                            "Programar y coordinar el uso de equipo de laboratorio",
                            "Asesorar el uso de equipo, insumos y materiales diversos",
                            "Monitorear e informar sobre la necesidad de insuos",
                            "Impartir cursos, talleres, charlas y ponencias",
                            "Promover y dar apoyo en estudios de investigacion tecnologica",
                            "Administrar evaluaciones",
                            "Calificar evaluaciones",
                            "Atender consultas",
                            "Impartir discusiones de la asignatura",
                            "Preparar material de apoyo",
                            "Elaborar evlualaciones"
                        ];
                        foreach ($activities as $activityName) {
                            $activity = Activity::where('name', 'ilike', $activityName)->first();
                            if (!$activity) {
                                $activity = Activity::create(['name' => $activityName]);
                            }
                            $activityIds[] = $activity->id;
                        }
                        $this->faker = Faker::create();
                        $user->assignRole('Candidato');
                        $user->person()->save(Person::factory()->make());
                        $user->person->is_employee = true;
                        $user->person->work_permission = 'PERMISO.pdf';
                        $user->person->save();
                        $personValidation = new PersonValidation(['person_id' => $user->person->id]);
                        $personValidation->save();
                        $employe = Employee::create([
                                'journey_type'      => $this->faker->randomElement(['tiempo-completo', 'medio-tiempo', 'cuarto-tiempo', 'tiempo-parcial', 'tiempo-eventual']),
                                'faculty_id'        => $this->faker->numberBetween(1, 10),
                                'escalafon_id'      => $this->faker->numberBetween(1, 5),
                                'person_id'         => $user->person->id,
                                'sub_partida'       => $this->faker->numberBetween(1, 18),
                                'partida'           => $this->faker->numberBetween(1, 50),
                            ]);
                        $employe->employeeTypes()->save($employeeTypes[0]);
                        $newStaySchedule = StaySchedule::create([
                                'semester_id'   => $semester->id,
                                'employee_id'  => $employe->id,
                            ]);
                        $newStaySchedule->scheduleDetails()->createMany($details);
                        $newStaySchedule->scheduleActivities()->sync($activityIds);
                        $task = new PersonController;
                        $r = $task->mergePersonalDoc($user->person->id);
                });
        }
}
