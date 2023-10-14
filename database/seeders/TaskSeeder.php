<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run()
    {
        foreach (Contract::all() as $contract) {
            Task::factory(rand(20, 100))->create([
                'contract_id' => $contract,
                'user_id' => User::first()]);
        }
    }
}
