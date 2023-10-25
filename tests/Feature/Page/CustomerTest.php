<?php

use App\Enums\CurrencyEnum;
use App\Livewire\Form\ContractForm;
use App\Livewire\Form\CustomerForm;
use App\Livewire\Table\ContractTable;
use App\Livewire\Table\CustomerTable;
use App\Models\Address;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\User;
use App\Policies\ContractPolicy;
use App\Policies\CustomerPolicy;
use Carbon\Carbon;
use Livewire\Livewire;
use Mockery\MockInterface;

use function Pest\Laravel\get;

beforeEach(function () {
    $this->user = User::factory()->create();
});

function getCustomer(User $user, array $attributes = [])
{
    return Customer::factory()
        ->recycle($user)
        ->create($attributes);
}

it('customer table page exists', function () {
    // Arrange & Act & Assert
    $this->actingAs($this->user);
    get(route('table.customer'))
        ->assertOk();
});

it('see CustomerTable component', function () {
    // Arrange & Act & Assert
    $this->actingAs($this->user);
    get(route('table.customer'))
        ->assertOk()
        ->assertSeeLivewire(CustomerTable::class);
});

it('see current user customer in table component', function () {
    // Arrange
    $customer = getCustomer($this->user);

    //  & Act & Assert
    Livewire::actingAs($this->user)
        ->test(CustomerTable::class)
        ->assertOk()
        ->assertSee($customer->name)
        ->assertSee($customer->registration_number);
});

it('table component dispatch event on edit button click', function () {
    // Arrange
    $customer = getCustomer($this->user);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(CustomerTable::class)
        ->call('edit', $customer->id)
        ->assertOk()
        ->assertDispatched('open-update-form-modal');
});

it('form component listen for event and open modal with customer data', function () {
    // Arrange
    $customer = getCustomer($this->user);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(CustomerForm::class)
        ->dispatch('open-update-form-modal', $customer->id)
        ->assertOk()
        ->assertSet('isModalOpen', true)
        ->assertSet('modelId', $customer->id)
        ->assertViewHas('address_id', $customer->address_id)
        ->assertViewHas('name', $customer->name)
        ->assertViewHas('registration_number', $customer->registration_number)
        ->assertViewHas('vat_number', $customer->vat_number);
});

it('form component update customer successfully', function () {
    // Arrange
    $customer = getCustomer($this->user);
    $newAddress = Address::factory()
        ->recycle($this->user)
        ->create();

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(CustomerForm::class)
        ->set('modelId', $customer->id)
        ->call('setDataForUpdate')
        ->set('address_id', $newAddress->id)
        ->set('name', 'new test tame')
        ->set('registration_number', 'new registration number')
        ->set('vat_number', 'new vat number')
        ->call('submit')
        ->assertOk()
        ->assertHasNoErrors();
    $this->assertDatabaseCount(Customer::class, 1);
    $this->assertDatabaseHas(Customer::class, [
        'id' => $customer->id,
        'address_id' => $newAddress->id,
        'name' => 'new test tame',
        'registration_number' => 'new registration number',
        'vat_number' => 'new vat number',
    ]);
});

it('form component create customer successfully', function () {
    $address = Address::factory()
        ->recycle($this->user)
        ->create();

    // Arrange & Act & Assert
    Livewire::actingAs($this->user)
        ->test(CustomerForm::class)
        ->call('openCreateModal')
        ->set('address_id', $address->id)
        ->set('name', 'new test tame')
        ->set('registration_number', 'new registration number')
        ->set('vat_number', 'new vat number')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertOk();
    $this->assertDatabaseCount(Customer::class, 1);
    $this->assertDatabaseHas(Customer::class, [
        'address_id' => $address->id,
        'name' => 'new test tame',
        'registration_number' => 'new registration number',
        'vat_number' => 'new vat number',
    ]);
});

it('table component can delete customer successfully', function () {
    // Arrange
    getCustomer($this->user);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(CustomerTable::class)
        ->call('selectPage', true)
        ->call('executeAction', 'delete')
        ->assertOk();
    $this->assertDatabaseCount(Customer::class, 0);
});

describe('authorization & visibility 👀', function () {
    it('page is secured by auth middleware', function () {
        // Arrange & Act & Assert
        get(route('table.customer'))
            ->assertRedirectToRoute('login');
    });

    it('table display only current user customer', function () {
        // Arrange
        $currentUserCustomer = getCustomer($this->user);
        $anotherUser = User::factory()->create();
        $anotherUserCustomer = getCustomer($anotherUser);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(CustomerTable::class)
            ->assertSee($currentUserCustomer->name)
            ->assertDontSee($anotherUserCustomer->name);
    });

    it('forbidden to delete because of delete policy', function () {
        // Arrange
        $this->partialMock(CustomerPolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('delete')
                ->andReturnFalse();
        });
        getCustomer($this->user);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(CustomerTable::class)
            ->call('selectPage', true)
            ->call('executeAction', 'delete')
            ->assertForbidden();
        $this->assertDatabaseCount(Customer::class, 1);
    });

    it('forbidden to open form because of update policy', function () {
        // Arrange
        $this->partialMock(CustomerPolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
                ->andReturnFalse();
        });
        $customer = getCustomer($this->user);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(CustomerForm::class)
            ->set('modelId', $customer->id)
            ->call('openUpdateModal', true)
            ->assertForbidden();
    });

    it('forbidden to update because of update policy', function () {
        // Arrange
        $this->partialMock(CustomerPolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
                ->andReturnFalse();
        });
        $customer = getCustomer($this->user);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(CustomerForm::class)
            ->set('modelId', $customer->id)
            ->call('submit', true)
            ->assertForbidden();
    });
});
