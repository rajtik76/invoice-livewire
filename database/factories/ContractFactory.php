<?php
declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CurrencyEnum;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ContractFactory extends Factory
{
    protected $model = Contract::class;

    public function definition(): array
    {
        return [
            'user_id' => fn() => User::factory(),
            'customer_id' => fn(array $attributes) => Customer::factory()->create(['user_id' => $attributes['user_id']]),
            'supplier_id' => fn(array $attributes) => Supplier::factory()->create(['user_id' => $attributes['user_id']]),
            'name' => $this->faker->slug(4),
            'signed_at' => $this->faker->unique()->dateTimeBetween(),
            'currency' => $this->faker->randomElement(CurrencyEnum::cases()),
            'price_per_hour' => fn(array $attributes) => $attributes['currency'] === CurrencyEnum::CZK
                ? $this->faker->randomFloat(2, 300, 1000)
                : $this->faker->randomFloat(2, 10, 50),
            'active' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
