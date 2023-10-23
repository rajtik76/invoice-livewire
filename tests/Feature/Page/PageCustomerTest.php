<?php

use App\Livewire\Table\CustomerTable;
use App\Models\Customer;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\get;

it('page exists', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    $this->actingAs($user);
    get(route('table.customer'))
        ->assertOk();
});

it('page is secured by auth middleware', function () {
    // Act & Assert
    get(route('table.customer'))
        ->assertRedirectToRoute('login');
});

it('see customer table livewire component', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    $this->actingAs($user);
    get(route('table.customer'))
        ->assertOk()
        ->assertSeeLivewire(CustomerTable::class);
});

it('see customer in table', function () {
    // Arrange
    $customer = Customer::factory()->create();

    // Act & Assert
    Livewire::actingAs(User::find($customer->user_id))
        ->test(CustomerTable::class)
        ->assertSee($customer->name)
        ->assertSee($customer->registration_number);
});

it('edit button dispatch event', function () {
    // Arrange
    $customer = Customer::factory()->create();

    // Act & Assert
    Livewire::actingAs(User::find($customer->user_id))
        ->test(CustomerTable::class)
        ->call('edit', $customer->id)
        ->assertDispatched('open-update-form-modal');
});

it('edit form listen for event and open modal with data', function () {
    // Arrange
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id]);

    // Act & Assert
    Livewire::actingAs($user)
        ->test('form.customer-form')
        ->dispatch('open-update-form-modal', $customer->id)
        ->assertSet('isModalOpen', true)
        ->assertSet('modelId', $customer->id)
        ->assertViewHas('address_id', $customer->address_id)
        ->assertViewHas('name', $customer->name)
        ->assertViewHas('registration_number', $customer->registration_number)
        ->assertViewHas('vat_number', $customer->vat_number);
});
