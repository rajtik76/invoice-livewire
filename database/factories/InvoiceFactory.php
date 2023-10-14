<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Contract;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    protected Contract $contract;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $date = Carbon::parse(fake()->unique()->date());

        $contentArray = [];
        for ($i = 0; $i < fake()->numberBetween(10, 20); $i++) {
            $contentArray[] = [
                'name' => 'WW-' . fake()->randomNumber(6, true) . ' ' . fake()->unique()->slug,
                'url' => fake()->boolean() ? fake()->url() : null,
                'hours' => fake()->randomFloat(1, 10, 50),
            ];
        }

        return [
            'contract_id' => Contract::factory(),
            'number' => $date->year . sprintf('%02d', $date->month) . fake()->unique()->randomNumber(3, true),
            'year' => $date->year,
            'month' => $date->month,
            'issue_date' => fake()->dateTimeBetween(now()->subYear()),
            'due_date' => fn (array $attributes) => (new Carbon($attributes['issue_date']))->clone()->addDays(7),
            'content' => $contentArray,
            'price_per_unit' => function (array $attributes) {
                $this->contract = Contract::findOrFail($attributes['contract_id']);

                return $this->contract->price_per_unit;
            },
            'total_amount' => fn (array $attributes) => collect($attributes['content'])->sum(fn ($item) => $item['hours']) * $attributes['price_per_unit'],
            'currency' => fn (array $attributes) => $this->contract->bank->currency,
        ];
    }
}
