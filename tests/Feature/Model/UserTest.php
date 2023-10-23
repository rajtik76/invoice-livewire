<?php

use App\Models\Address;
use App\Models\BankAccount;
use App\Models\Customer;
use App\Models\User;

it('has addresss', function () {
    // Arrange
    $user = User::factory()->create();
    $address = Address::factory(3)->create(['user_id' => $user->id]);

    // Assert & Act
    expect($user->addresses)
        ->toHaveCount(3)
        ->each()
        ->toBeInstanceOf(Address::class);
});

it('has bank accounts', function () {
    // Arrange
    $user = User::factory()->create();
    $address = BankAccount::factory(3)->create(['user_id' => $user->id]);

    // Assert & Act
    expect($user->bankAccounts)
        ->toHaveCount(3)
        ->each()
        ->toBeInstanceOf(BankAccount::class);
});

it('has customers', function () {
    // Arrange
    $user = User::factory()->create();
    $address = Customer::factory(3)->create(['user_id' => $user->id]);

    // Assert & Act
    expect($user->customers)
        ->toHaveCount(3)
        ->each()
        ->toBeInstanceOf(Customer::class);
});
