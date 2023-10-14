<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\Invoice;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run()
    {
        foreach (Contract::all() as $contract) {
            Invoice::factory(10)->create(['contract_id' => $contract]);
        }
    }
}
