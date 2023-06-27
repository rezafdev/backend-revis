<?php

namespace Database\Factories;

use App\Models\Therapy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TherapyPhoto>
 */
class TherapyPhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $therapyId = Therapy::inRandomOrder()->first()->id;
        $s = $this->faker->randomElement(['01', '02', '03', '04', '05', '06']);
        $path = "f/therapy/$s.png";
        return [
            'therapyId' => $therapyId,
            'path' => $path,
        ];
    }
}
