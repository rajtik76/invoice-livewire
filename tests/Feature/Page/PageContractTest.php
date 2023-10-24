<?php

use App\Livewire\Table\ContractTable;
use App\Models\Contract;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\get;

it('page exists', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    $this->actingAs($user);
    get(route('table.contract'))
        ->assertOk();
});

it('page is secured by auth middleware', function () {
    // Act & Assert
    get(route('table.contract'))
        ->assertRedirectToRoute('login');
});

it('see contract table livewire component', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    $this->actingAs($user);
    get(route('table.contract'))
        ->assertOk()
        ->assertSeeLivewire(ContractTable::class);
});

it('see contract in table', function () {
    // Arrange
    $contract = Contract::factory()->create();

    // Act & Assert
    Livewire::actingAs(User::find($contract->user_id))
        ->test(ContractTable::class)
        ->assertSee($contract->name)
        ->assertSee($contract->customer->name)
        ->assertSee($contract->supplier->name)
        ->assertSee($contract->signed_at->toDateString())
        ->assertSee($contract->price_per_hour);
});

it('edit button dispatch event', function () {
    // Arrange
    $contract = Contract::factory()->create();

    // Act & Assert
    Livewire::actingAs(User::find($contract->user_id))
        ->test(ContractTable::class)
        ->call('edit', $contract->id)
        ->assertDispatched('open-update-form-modal');
});

it('edit form listen for event and open modal with data', function () {
    // Arrange
    $user = User::factory()->create();
    $contract = Contract::factory()->create(['user_id' => $user->id]);

    // Act & Assert
    Livewire::actingAs($user)
        ->test('form.contract-form')
        ->dispatch('open-update-form-modal', $contract->id)
        ->assertSet('isModalOpen', true)
        ->assertSet('modelId', $contract->id)
        ->assertViewHas('customer_id', $contract->customer_id)
        ->assertViewHas('supplier_id', $contract->supplier_id)
        ->assertViewHas('name', $contract->name)
        ->assertViewHas('active', $contract->active)
        ->assertViewHas('signed_at', $contract->signed_at->toDateString())
        ->assertViewHas('price_per_hour', $contract->price_per_hour)
        ->assertViewHas('currency', $contract->currency->value);
});

it('can delete', function () {
    // Arrange
    $user = User::factory()->create();
    Contract::factory()->create(['user_id' => $user->id]);

    // Act & Assert
    Livewire::actingAs($user)
        ->test(ContractTable::class)
        ->call('selectPage', true)
        ->call('executeAction', 'delete');
    $this->assertDatabaseCount(Contract::class, 0);
});
