<?php

use App\Models\Address;
use App\Models\BankAccount;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Task;
use App\Models\TaskHour;
use App\Models\User;

/**
 * @param  class-string  $model
 */
function seedModel(string $model, int $count = 3): User
{
    $user = User::factory()->create();
    $model::factory($count)
        ->recycle($user)
        ->create();

    return $user;
}

it('user sees all necessary relations', function () {
    // Arrange
    $models = [
        Address::class,
        BankAccount::class,
        Customer::class,
        Supplier::class,
        Contract::class,
        Task::class,
        TaskHour::class,
    ];

    // Assert & Act
    foreach ($models as $model) {
        $relation = str((new ReflectionClass($model))->getShortName())
            ->plural()
            ->lcfirst();

        expect(seedModel($model)->$relation)
            ->toHaveCount(3)
            ->each()
            ->toBeInstanceOf($model);
    }
});
