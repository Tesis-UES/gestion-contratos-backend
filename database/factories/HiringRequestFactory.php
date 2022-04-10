<?php

namespace Database\Factories;
use App\Models\HiringRequest;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Http\Traits\GeneratorTrait;
use App\Constants\HiringRequestStatusCode;

class HiringRequestFactory extends Factory
{
    protected $model = HiringRequest::class;
    use GeneratorTrait;
    public function definition()
    {
        $id = $this->faker->numberBetween(1, 10);
        return [
            'code' => $this->generateRequestCode($id),
            'request_status' => HiringRequestStatusCode::RDC,
            'contract_type_id' => $this->faker->numberBetween(1, 3),
            'school_id' => $id,
            'modality' => $this->faker->randomElement(['Modalidad Presencial', 'Modalidad en Linea', 'Modalidad Semi-Presencial']),
            'message' => $this->faker->randomHtml(2,3),
        ];
    }
}
