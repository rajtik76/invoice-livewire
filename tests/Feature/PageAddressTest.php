<?php


use Livewire\Livewire;
use App\Livewire\Table\AddressTable;
use App\Models\Address;
use App\Models\User;
use Livewire\Volt\Volt;
use function Pest\Laravel\get;

it('page exists', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    $this->actingAs($user);
    get(route('address.table'))
        ->assertOk();
});

it('page is secured by auth middleware', function () {
    // Act & Assert
    get(route('address.table'))
        ->assertRedirectToRoute('login');
});

it('see address table livewire component', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    $this->actingAs($user);
    get(route('address.table'))
        ->assertOk()
        ->assertSeeLivewire(AddressTable::class);
});

it('see address', function () {
    // Arrange
    $address = Address::factory()->create();

    // Act & Assert
    Livewire::actingAs(User::find($address->user_id))
        ->test(AddressTable::class)
        ->assertSee($address->street)
        ->assertSee($address->city)
        ->assertSee($address->zip);
});

it('see address country as enum country name', function () {
    // Arrange
    $address = Address::factory()->create();

    // Act & Assert
    Livewire::actingAs(User::find($address->user_id))
        ->test(AddressTable::class)
        ->assertSee($address->country->countryName())
        ->assertDontSee($address->country->value);
});

it('edit button dispatch event', function () {
    // Arrange
    $address = Address::factory()->create();

    // Act & Assert
    Livewire::actingAs(User::find($address->user_id))
        ->test(AddressTable::class)
        ->call('editAddress', $address->id)
        ->assertDispatched('open-update-form-modal');
});

it('edit form listen for event and open modal with data', function () {
    // Arrange
    $user = User::factory()->create();
    $address = Address::factory()->create(['user_id' => $user->id]);

    // Act & Assert
    Livewire::actingAs($user)
        ->test('address-form')
        ->dispatch('open-update-form-modal', $address->id)
        ->assertSet('isModalOpen', true)
        ->assertSet('modelId', $address->id)
        ->assertViewHas('street', $address->street)
        ->assertViewHas('city', $address->city)
        ->assertViewHas('zip', $address->zip)
        ->assertViewHas('country', $address->country);
});
