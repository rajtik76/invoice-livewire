<?php

use App\Models\Address;
use App\Models\User;

it('has addresses', function () {
    // Arrange
    $user = User::factory()->create();
    $address = Address::factory(3)->create(['user_id' => $user->id]);

    // Assert & Act
    expect($user->addresses)
        ->toHaveCount(3)
        ->each()
        ->toBeInstanceOf(Address::class);
});
