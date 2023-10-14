<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Address;
use App\Models\CompanyDetail;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'company_detail_id' => CompanyDetail::factory(),
            'address_id' => Address::factory(),
            'name' => fake()->company(),
        ];
    }
}
