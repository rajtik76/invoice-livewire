<?php

use App\Livewire\Table\SupplierTable;
use App\Models\Supplier;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\get;

it('page exists', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    $this->actingAs($user);
    get(route('table.supplier'))
        ->assertOk();
});

it('page is secured by auth middleware', function () {
    // Act & Assert
    get(route('table.supplier'))
        ->assertRedirectToRoute('login');
});

it('see supplier table livewire component', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    $this->actingAs($user);
    get(route('table.supplier'))
        ->assertOk()
        ->assertSeeLivewire(SupplierTable::class);
});

it('see supplier in table', function () {
    // Arrange
    $supplier = Supplier::factory()->create();

    // Act & Assert
    Livewire::actingAs(User::find($supplier->user_id))
        ->test(SupplierTable::class)
        ->assertSee($supplier->name)
        ->assertSee($supplier->registration_number)
        ->assertSee($supplier->vat_number);
});

it('edit button dispatch event', function () {
    // Arrange
    $supplier = Supplier::factory()->create();

    // Act & Assert
    Livewire::actingAs(User::find($supplier->user_id))
        ->test(SupplierTable::class)
        ->call('edit', $supplier->id)
        ->assertDispatched('open-update-form-modal');
});

it('edit form listen for event and open modal with data', function () {
    // Arrange
    $user = User::factory()->create();
    $supplier = Supplier::factory()->create(['user_id' => $user->id]);

    // Act & Assert
    Livewire::actingAs($user)
        ->test('form.supplier-form')
        ->dispatch('open-update-form-modal', $supplier->id)
        ->assertSet('isModalOpen', true)
        ->assertSet('modelId', $supplier->id)
        ->assertViewHas('address_id', $supplier->address_id)
        ->assertViewHas('bank_account_id', $supplier->bank_account_id)
        ->assertViewHas('name', $supplier->name)
        ->assertViewHas('registration_number', $supplier->registration_number)
        ->assertViewHas('vat_number', $supplier->vat_number)
        ->assertViewHas('email', $supplier->email)
        ->assertViewHas('phone', $supplier->phone);
});
