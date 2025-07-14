<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pelamar>
 */
class PelamarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->randomNumber(1,49),
            'job_id' => fake()->randomNumber(1,99),
            'status' => fake()->randomElement(['tunda', 'interview', 'ditolak','Menunggu Pekerjaan','Sedang Bekerja','Menuggu Pembayaran','Gagal','selesai']),
            'Tipe_Group' => fake()->randomElement(['Sendiri'])
        ];
    }
}
