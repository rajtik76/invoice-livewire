<?php

namespace Database\Factories;

use App\Models\Contract;
use App\Models\Invoice;
use App\Models\User;
use App\Services\InvoiceComputedData;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'user_id' => fn () => User::factory(),
            'contract_id' => fn () => Contract::factory(),
            'issue_date' => $this->faker->dateTimeBetween('2000-01-01'),
            'due_date' => fn (array $attributes) => \Carbon\Carbon::parse($attributes['issue_date'])->addDays(7),
            'year' => fn (array $attributes) => \Carbon\Carbon::parse($attributes['issue_date'])->year,
            'month' => fn (array $attributes) => \Carbon\Carbon::parse($attributes['issue_date'])->month,
            'number' => fn (array $attributes) => $attributes['year'].$attributes['month'].$this->faker->unique()->numerify('###'),
            'content' => fn (array $attributes) => $this->getInvoiceComputedDataService($attributes)->getContent(),
            'total_amount' => fn (array $attributes) => $this->getInvoiceComputedDataService($attributes)->getTotalAmount(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    private function getInvoiceComputedDataService(array $attributes): InvoiceComputedData
    {
        $contractId = $attributes['contract_id'];
        $year = $attributes['year'];
        $month = $attributes['month'];

        return app(InvoiceComputedData::class, [
            'contractId' => $contractId,
            'year' => $year,
            'month' => $month,
        ]);
    }
}
