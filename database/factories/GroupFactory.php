<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Group::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'number'            => $this->faker->numberBetween(1, 50),
            'group_type_id'     => $this->faker->numberBetween(1, 3), 
            'academic_load_id'  => 6,
            'course_id'         => $this->faker->numberBetween(1, 38),
            'status'            => 'SDA',
            'modality'          => $this->faker->randomElement(['Presencial', 'En Linea']),
        ];
    }
}
