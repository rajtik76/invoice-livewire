<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\CompanyDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_detail_id' => CompanyDetail::factory(),
            'address_id' => Address::factory(),
            'name' => fake()->company(),
        ];
    }
}
