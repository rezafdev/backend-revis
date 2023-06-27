<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Therapy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $patientId = Patient::inRandomOrder()->first()->id;
        $therapyId = Therapy::inRandomOrder()->first()->id;
        $beginDate = $this->faker->dateTimeBetween('-20 days', '+5 days')->setTime(
            $this->faker->numberBetween(8, 18),
            $this->faker->randomElement([0, 15, 30, 45]),
        );

        $now = Carbon::now();
        $passedTime = $now->greaterThan($beginDate);

        return [
            'patientId' => $patientId,
            'therapyId' => $therapyId,
            'beginDate' => $beginDate->format('Y-m-d'),
            'beginTime' => $beginDate->format('H:i'),
            'status' => !$passedTime ? Appointment::STATUS_SCHEDULED: ( $this->faker->boolean(80) ? Appointment::STATUS_SCHEDULED : Appointment::STATUS_FINISHED),
        ];
    }
}
