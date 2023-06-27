<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends Factory<Doctor>
 */
class DoctorFactory extends Factory
{
    const FILES = [
        'f/people/Amelia.png',
        'f/people/Ava.png',
        'f/people/Benjamin.png',
        'f/people/Charlotte.png',
        'f/people/Elijah.png',
        'f/people/Emma.png',
        'f/people/James.png',
        'f/people/Liam.png',
        'f/people/Lucas.png',
        'f/people/Oliver.png',
        'f/people/Olivia.png',
    ];



    public function definition(): array
    {
        $skill_mental = $this->faker->boolean;
        $skill_blood = $this->faker->boolean;
        $skill_beauty = !$skill_mental && !$skill_blood ? true : $this->faker->boolean;

        return [
            'name' => $this->faker->name,
            'type' => $this->faker->boolean(70) ? Doctor::TYPE_DOCTOR : Doctor::TYPE_NURSE,
            'bio' => $this->faker->paragraph(4),
            'skill_mental' => $skill_mental,
            'skill_beauty' => $skill_beauty,
            'skill_blood' => $skill_blood,
            'avatarUrl' => $this->faker->randomElement(self::FILES),
        ];
    }
}
