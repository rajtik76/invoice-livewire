<?php

use App\Enums\CurrencyEnum;
use App\Livewire\Form\ContractForm;
use App\Livewire\Table\ContractTable;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\User;
use App\Policies\ContractPolicy;
use Carbon\Carbon;
use Livewire\Livewire;
use Mockery\MockInterface;

use function Pest\Laravel\get;

beforeEach(function () {
    $this->user = User::factory()->create();
});

function getContract(User $user, array $attributes = [])
{
    return Contract::factory()
        ->recycle($user)
        ->create($attributes);
}

it('contract table page exists', function () {
    // Arrange & Act & Assert
    $this->actingAs($this->user);
    get(route('table.contract'))
        ->assertOk();
});

it('see ContractTable component', function () {
    // Arrange & Act & Assert
    $this->actingAs($this->user);
    get(route('table.contract'))
        ->assertOk()
        ->assertSeeLivewire(ContractTable::class);
});

it('see current user contract in table component', function () {
    // Arrange
    $contract = getContract($this->user);

    //  & Act & Assert
    Livewire::actingAs($this->user)
        ->test(ContractTable::class)
        ->assertOk()
        ->assertSee($contract->name)
        ->assertSee($contract->signed_at->toDateString())
        ->assertSee($contract->price_per_hour)
        ->assertSee($contract->currency->getCurrencySymbol());
});

it('table component dispatch event on edit button click', function () {
    // Arrange
    $contract = getContract($this->user);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(ContractTable::class)
        ->call('edit', $contract->id)
        ->assertOk()
        ->assertDispatched('open-update-form-modal');
});

it('form component listen for event and open modal with contract data', function () {
    // Arrange
    $contract = getContract($this->user);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(ContractForm::class)
        ->dispatch('open-update-form-modal', $contract->id)
        ->assertOk()
        ->assertSet('isModalOpen', true)
        ->assertSet('modelId', $contract->id)
        ->assertViewHas('customer_id', $contract->customer_id)
        ->assertViewHas('supplier_id', $contract->supplier_id)
        ->assertViewHas('name', $contract->name)
        ->assertViewHas('signed_at', $contract->signed_at->toDateString())
        ->assertViewHas('price_per_hour', $contract->price_per_hour)
        ->assertViewHas('currency', $contract->currency->value);
});

it('form component update contract successfully', function () {
    // Arrange
    $contract = getContract($this->user, [
        'active' => true,
        'currency' => CurrencyEnum::EUR,
    ]);
    $newCustomer = Customer::factory()
        ->recycle($this->user)
        ->create();
    $newSupplier = Supplier::factory()
        ->recycle($this->user)
        ->create();

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(ContractForm::class)
        ->set('modelId', $contract->id)
        ->call('setDataForUpdate')
        ->set('customer_id', $newCustomer->id)
        ->set('supplier_id', $newSupplier->id)
        ->set('name', 'new test tame')
        ->set('signed_at', '2000-01-01')
        ->set('price_per_hour', 11.11)
        ->set('currency', CurrencyEnum::CZK->value)
        ->set('active', false)
        ->call('submit')
        ->assertOk()
        ->assertHasNoErrors();
    $this->assertDatabaseCount(Contract::class, 1);
    $this->assertDatabaseHas(Contract::class, [
        'id' => $contract->id,
        'customer_id' => $newCustomer->id,
        'supplier_id' => $newSupplier->id,
        'name' => 'new test tame',
        'signed_at' => Carbon::create(2000, 1, 1),
        'active' => false,
    ]);
});

it('form component create contract successfully', function () {
    $customer = Customer::factory()
        ->recycle($this->user)
        ->create();
    $supplier = Supplier::factory()
        ->recycle($this->user)
        ->create();

    // Arrange & Act & Assert
    Livewire::actingAs($this->user)
        ->test(ContractForm::class)
        ->call('openCreateModal')
        ->set('customer_id', $customer->id)
        ->set('supplier_id', $supplier->id)
        ->set('name', 'new test tame')
        ->set('signed_at', '2000-02-02')
        ->set('price_per_hour', 99.99)
        ->set('currency', CurrencyEnum::CZK->value)
        ->set('active', false)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertOk();
    $this->assertDatabaseCount(Contract::class, 1);
    $this->assertDatabaseHas(Contract::class, [
        'customer_id' => $customer->id,
        'supplier_id' => $supplier->id,
        'name' => 'new test tame',
        'signed_at' => Carbon::create(2000, 2, 2),
        'price_per_hour' => 99.99,
        'currency' => CurrencyEnum::CZK,
        'active' => false,
    ]);
});

it('table component can deactivate contract', function () {
    // Arrange
    $contract = getContract($this->user, ['active' => true]);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(ContractTable::class)
        ->call('selectPage', true)
        ->call('executeAction', 'deactivate')
        ->assertOk();
    $this->assertDatabaseHas(Contract::class, [
        'id' => $contract->id,
        'active' => false,
    ]);
});

it('table component can activate contract', function () {
    // Arrange
    $contract = getContract($this->user, ['active' => false]);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(ContractTable::class)
        ->call('selectPage', true)
        ->call('executeAction', 'activate')
        ->assertOk();
    $this->assertDatabaseHas(Contract::class, [
        'id' => $contract->id,
        'active' => true,
    ]);
});

it('table component can delete contract successfully', function () {
    // Arrange
    getContract($this->user);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(ContractTable::class)
        ->call('selectPage', true)
        ->call('executeAction', 'delete')
        ->assertOk();
    $this->assertDatabaseCount(Contract::class, 0);
});

describe('authorization & visibility 👀', function () {
    it('page is secured by auth middleware', function () {
        // Arrange & Act & Assert
        get(route('table.contract'))
            ->assertRedirectToRoute('login');
    });

    it('table display only current user contract', function () {
        // Arrange
        $currentUserContract = getContract($this->user);
        $anotherUser = User::factory()->create();
        $anotherUserContract = getContract($anotherUser);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(ContractTable::class)
            ->assertSee($currentUserContract->name)
            ->assertDontSee($anotherUserContract->name);
    });

    it('forbidden to delete because of delete policy', function () {
        // Arrange
        $this->partialMock(ContractPolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('delete')
                ->andReturnFalse();
        });
        getContract($this->user);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(ContractTable::class)
            ->call('selectPage', true)
            ->call('executeAction', 'delete')
            ->assertForbidden();
        $this->assertDatabaseCount(Contract::class, 1);
    });

    it('forbidden to open form because of update policy', function () {
        // Arrange
        $this->partialMock(ContractPolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
                ->andReturnFalse();
        });
        $contract = getContract($this->user);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(ContractForm::class)
            ->set('modelId', $contract->id)
            ->call('openUpdateModal', true)
            ->assertForbidden();
    });

    it('forbidden to update because of update policy', function () {
        // Arrange
        $this->partialMock(ContractPolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
                ->andReturnFalse();
        });
        $contract = getContract($this->user);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(ContractForm::class)
            ->set('modelId', $contract->id)
            ->call('submit', true)
            ->assertForbidden();
    });

    it('forbidden to activate because of update policy', function () {
        // Arrange
        $this->partialMock(ContractPolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
                ->andReturnFalse();
        });
        $contract = getContract($this->user, ['active' => false]);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(ContractTable::class)
            ->call('selectPage', true)
            ->call('executeAction', 'activate')
            ->assertForbidden();
        $this->assertDatabaseHas(Contract::class, [
            'id' => $contract->id,
            'active' => false,
        ]);
    });

    it('forbidden to deactivate because of update policy', function () {
        // Arrange
        $this->partialMock(ContractPolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
                ->andReturnFalse();
        });
        $contract = getContract($this->user, ['active' => true]);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(ContractTable::class)
            ->call('selectPage', true)
            ->call('executeAction', 'deactivate')
            ->assertForbidden();
        $this->assertDatabaseHas(Contract::class, [
            'id' => $contract->id,
            'active' => true,
        ]);
    });
});
