<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder
{
    public function run()
    {
        $supplier = Supplier::factory()->create(['name' => 'Vladislav Rajtmajer']);

        Contract::factory(4)->create(['supplier_id' => $supplier]);
    }
}
