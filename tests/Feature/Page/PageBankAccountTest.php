<?php

use App\Livewire\Table\BankAccountTable;
use App\Models\BankAccount;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\get;

it('page exists', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    $this->actingAs($user);
    get(route('table.bank-account'))
        ->assertOk();
});

it('page is secured by auth middleware', function () {
    // Act & Assert
    get(route('table.bank-account'))
        ->assertRedirectToRoute('login');
});

it('see bank account table livewire component', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    $this->actingAs($user);
    get(route('table.bank-account'))
        ->assertOk()
        ->assertSeeLivewire(BankAccountTable::class);
});

it('see bank account', function () {
    // Arrange
    $bankAccount = BankAccount::factory()->create();

    // Act & Assert
    Livewire::actingAs(User::find($bankAccount->user_id))
        ->test(BankAccountTable::class)
        ->assertSee($bankAccount->user_id)
        ->assertSee($bankAccount->bank_name)
        ->assertSee($bankAccount->bank_number)
        ->assertSee($bankAccount->account_number)
        ->assertSee($bankAccount->swift)
        ->assertSee($bankAccount->iban);
});

it('edit button dispatch event', function () {
    // Arrange
    $bankAccount = BankAccount::factory()->create();

    // Act & Assert
    Livewire::actingAs(User::find($bankAccount->user_id))
        ->test(BankAccountTable::class)
        ->call('edit', $bankAccount->id)
        ->assertDispatched('open-update-form-modal');
});

it('edit form listen for event and open modal with data', function () {
    // Arrange
    $user = User::factory()->create();
    $bankAccount = BankAccount::factory()->create(['user_id' => $user->id]);

    // Act & Assert
    Livewire::actingAs($user)
        ->test('form.bank-account-form')
        ->dispatch('open-update-form-modal', $bankAccount->id)
        ->assertSet('isModalOpen', true)
        ->assertSet('modelId', $bankAccount->id)
        ->assertViewHas('bank_name', $bankAccount->bank_name)
        ->assertViewHas('account_number', $bankAccount->account_number)
        ->assertViewHas('bank_number', $bankAccount->bank_number)
        ->assertViewHas('iban', $bankAccount->iban)
        ->assertViewHas('swift', $bankAccount->swift);
});
