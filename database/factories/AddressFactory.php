<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CountryEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => fn () => User::factory(),
            'street' => $this->faker->streetName(),
            'city' => $this->faker->city(),
            'zip' => $this->faker->postcode(),
            'country' => $this->faker->randomElement(CountryEnum::cases()),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
