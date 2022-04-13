<?php

namespace Database\Factories;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // get an hour H:i and sum 2 hours 
        $sthour = $this->faker->time('H:i');
        $endhour = date('H:i', strtotime($sthour) + 7200);

        return [
            'day' => $this->faker->randomElement(['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes','Sabado']),
            'start_hour' =>  $sthour,
            'finish_hour' => $endhour,
        ];
    }
}
