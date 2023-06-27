<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Therapy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Therapy>
 */
class TherapyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $doctorId = Doctor::inRandomOrder()->first()->id;
        return [
            'name' => $this->faker->randomElement([ 'Pain Therapy 1', 'Pain Therapy 2', 'Pain Therapy 3']),
            'description' => $this->faker->paragraphs(2, true),
            'category' => $this->faker->randomElement(Therapy::CATEGORIES),
            'minDuration' => $this->faker->randomElement([10, 25, 30]),
            'maxDuration' => $this->faker->randomElement([60, 75, 90]),
            'doctorId' => $doctorId,
        ];
    }
}
