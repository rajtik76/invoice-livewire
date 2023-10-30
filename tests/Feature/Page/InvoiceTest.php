<?php

use App\Livewire\Form\InvoiceForm;
use App\Livewire\Table\InvoiceTable;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Task;
use App\Models\TaskHour;
use App\Models\User;
use App\Policies\InvoicePolicy;
use Carbon\Carbon;
use Livewire\Livewire;
use Mockery\MockInterface;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->user = User::factory()->create();
});

function getInvoice(User $user, array $attributes = []): Invoice
{
    return Invoice::factory()
        ->recycle($user)
        ->create($attributes);
}

it('invoice table page exists', function () {
    // Arrange & Act & Assert
    $this->actingAs($this->user);
    get(route('table.invoice'))
        ->assertOk();
});

it('see InvoiceTable component', function () {
    // Arrange & Act & Assert
    $this->actingAs($this->user);
    get(route('table.invoice'))
        ->assertOk()
        ->assertSeeLivewire(InvoiceTable::class);
});

it('see current user invoice in table component', function () {
    // Arrange
    $invoice = getInvoice($this->user);

    //  & Act & Assert
    Livewire::actingAs($this->user)
        ->test(InvoiceTable::class)
        ->assertOk()
        ->assertSee($invoice->contract_id)
        ->assertSee($invoice->number)
        ->assertSee($invoice->year)
        ->assertSee($invoice->month);
});

it('table component dispatch event on edit button click', function () {
    // Arrange
    $invoice = getInvoice($this->user);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(InvoiceTable::class)
        ->call('edit', $invoice->id)
        ->assertOk()
        ->assertDispatched('open-update-form-modal');
});

it('form component listen for event and open modal with task data', function () {
    // Arrange
    $invoice = getInvoice($this->user);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(InvoiceForm::class)
        ->dispatch('open-update-form-modal', $invoice->id)
        ->assertOk()
        ->assertSet('isModalOpen', true)
        ->assertSet('modelId', $invoice->id)
        ->assertViewHas('contract_id', $invoice->contract_id)
        ->assertViewHas('number', $invoice->number)
        ->assertViewHas('year', $invoice->year)
        ->assertViewHas('month', $invoice->month)
        ->assertViewHas('issue_date', $invoice->issue_date->toDateString())
        ->assertViewHas('due_date', $invoice->due_date->toDateString());
});

it('form component update invoice successfully', function () {
    // Arrange
    $invoice = getInvoice($this->user);
    $newContract = Contract::factory()
        ->recycle($this->user)
        ->create();

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(InvoiceForm::class)
        ->set('modelId', $invoice->id)
        ->call('setDataForUpdate')
        ->set('contract_id', $newContract->id)
        ->set('number', '2000001')
        ->set('year', 2000)
        ->set('month', 12)
        ->set('issue_date', '2000-12-12')
        ->set('due_date', '2000-12-31')
        ->call('submit')
        ->assertOk()
        ->assertHasNoErrors();
    $this->assertDatabaseCount(Invoice::class, 1);
    $this->assertDatabaseHas(Invoice::class, [
        'id' => $invoice->id,
        'contract_id' => $newContract->id,
        'number' => 2000001,
        'year' => 2000,
        'month' => 12,
        'issue_date' => Carbon::create(2000, 12, 12),
        'due_date' => Carbon::create(2000, 12, 31),
    ]);
});

it('form component create invoice successfully', function () {
    $contract = Contract::factory()
        ->recycle($this->user)
        ->create();

    // Arrange & Act & Assert
    Livewire::actingAs($this->user)
        ->test(InvoiceForm::class)
        ->call('openCreateModal')
        ->set('contract_id', $contract->id)
        ->set('number', '2000001')
        ->set('year', 2000)
        ->set('month', 12)
        ->set('issue_date', '2000-12-12')
        ->set('due_date', '2000-12-31')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertOk();
    $this->assertDatabaseCount(Invoice::class, 1);
    $this->assertDatabaseHas(Invoice::class, [
        'contract_id' => $contract->id,
        'number' => 2000001,
        'year' => 2000,
        'month' => 12,
        'issue_date' => Carbon::create(2000, 12, 12),
        'due_date' => Carbon::create(2000, 12, 31),
    ]);
});

it('calculate invoice content and amount properly', function () {
    $task = Task::factory()->create(['user_id' => $this->user]);
    $taskHours = TaskHour::factory()->create([
        'user_id' => $this->user,
        'task_id' => $task->id,
        'date' => '2000-12-12',
    ]);
    $contract = $task->contract;

    // Arrange & Act & Assert
    Livewire::actingAs($this->user)
        ->test(InvoiceForm::class)
        ->call('openCreateModal')
        ->set('contract_id', $contract->id)
        ->set('number', '2000001')
        ->set('year', 2000)
        ->set('month', 12)
        ->set('issue_date', '2000-12-12')
        ->set('due_date', '2000-12-31')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertOk();
    $this->assertDatabaseCount(Invoice::class, 1);
    $this->assertDatabaseHas(Invoice::class, [
        'contract_id' => $contract->id,
        'number' => 2000001,
        'year' => 2000,
        'month' => 12,
        'issue_date' => Carbon::create(2000, 12, 12),
        'due_date' => Carbon::create(2000, 12, 31),
        'total_amount' => $taskHours->sum('hours') * $contract->price_per_hour,
        'content' => json_encode([
            1 => [
                'name' => $task->name,
                'url' => $task->url,
                'hours' => $taskHours->hours,
            ],
        ])
    ]);
});

it('table component can delete invoice successfully', function () {
    // Arrange
    getInvoice($this->user);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(InvoiceTable::class)
        ->call('selectPage', true)
        ->call('executeAction', 'delete')
        ->assertOk();
    $this->assertDatabaseCount(Invoice::class, 0);
});

describe('authorization & visibility 👀', function () {
    it('page is secured by auth middleware', function () {
        // Arrange & Act & Assert
        get(route('table.invoice'))
            ->assertRedirectToRoute('login');
    });

    it('table display only current user tasks', function () {
        // Arrange
        $currentUserInvoice = getInvoice($this->user, ['number' => 1234567]);
        $anotherUser = User::factory()->create();
        $anotherUserInvoice = getInvoice($anotherUser, ['number' => 9876543]);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(InvoiceTable::class)
            ->assertSee(1234567)
            ->assertDontSee(9876543);
    });

    it('forbidden to delete because of delete policy', function () {
        // Arrange
        $this->partialMock(InvoicePolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('delete')
                ->andReturnFalse();
        });
        getInvoice($this->user);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(InvoiceTable::class)
            ->call('selectPage', true)
            ->call('executeAction', 'delete')
            ->assertForbidden();
        $this->assertDatabaseCount(Invoice::class, 1);
    });

    it('forbidden to open form because of update policy', function () {
        // Arrange
        $this->partialMock(InvoicePolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
                ->andReturnFalse();
        });
        $invoice = getInvoice($this->user);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(InvoiceForm::class)
            ->set('modelId', $invoice->id)
            ->call('openUpdateModal', true)
            ->assertForbidden();
    });

    it('forbidden to update because of update policy', function () {
        // Arrange
        $this->partialMock(InvoicePolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
                ->andReturnFalse();
        });
        $invoice = getInvoice($this->user);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(InvoiceForm::class)
            ->set('modelId', $invoice->id)
            ->call('submit', true)
            ->assertForbidden();
    });
});
