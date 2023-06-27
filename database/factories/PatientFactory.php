<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $male = $this->faker->boolean;

        return [
            'name' => $male ? $this->faker->firstNameMale : $this->faker->firstNameFemale,
            'surname' => $this->faker->lastName,
            'sexType' => $male,
            'birthday' => $this->faker->dateTimeBetween('-40 years', '-18 years')->format('Y/m/d'),
            'email' => $this->faker->email,
            'status' => $this->faker->randomElement([0, 1, 0, 0, 0]),
            'phoneCountryCode' => "+43",
            'phoneNumber' => substr($this->faker->phoneNumber, -10),
            'created_at' => $this->faker->dateTimeBetween('-30 days', '-1 days')->format('Y-m-d H:i:s'),
        ];
    }
}
