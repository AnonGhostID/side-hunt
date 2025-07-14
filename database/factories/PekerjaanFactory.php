<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pekerjaan>
 */
class PekerjaanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "kriteria" => "",
            'latitude' => $this->faker->latitude(-7.38, -7.21),      // hanya daratan Surabaya
            'longitude' => $this->faker->longitude(112.65, 112.84),  // kisaran Surabaya
            'koordinat' => function (array $attributes) {
                return $attributes['latitude'] . ',' . $attributes['longitude'];
            },
            "start_job" => now()->addDays(7)->toDateTimeString(),
            "end_job" => now()->addDays(fake()->numberBetween(5, 15))->toDateTimeString(),
            "deadline_job" => now()->addDays(3)->toDateTimeString(),
        ];
    }
}
