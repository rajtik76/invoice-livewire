<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyDetail>
 */
class CompanyDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'vat_number' => fake()->randomNumber(9, true),
            'registration_number' => fake()->randomNumber(9, true),
            'mobile' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
        ];
    }
}
