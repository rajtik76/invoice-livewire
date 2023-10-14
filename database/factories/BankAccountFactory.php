<?php

namespace Database\Factories;

use App\Models\Enums\BankAccountCurrencyEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BankAccount>
 */
class BankAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'bank_name' => fake()->company(),
            'bank_code' => fake()->randomNumber(4, true),
            'number' => fake()->randomNumber(9, true),
            'iban' => fake()->iban(),
            'swift' => fake()->text(6),
            'currency' => fake()->randomElement(BankAccountCurrencyEnum::cases()),
        ];
    }
}
