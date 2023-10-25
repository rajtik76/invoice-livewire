<?php

use App\Livewire\Form\TaskForm;
use App\Livewire\Table\ContractTable;
use App\Livewire\Table\TaskTable;
use App\Models\Contract;
use App\Models\Task;
use App\Models\TaskHour;
use App\Models\User;
use App\Policies\TaskPolicy;
use Livewire\Livewire;

use Mockery\MockInterface;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('task table page exists', function () {
    // Arrange & Act & Assert
    $this->actingAs($this->user);
    get(route('table.task'))
        ->assertOk();
});

it('see TaskTable component', function () {
    // Arrange & Act & Assert
    $this->actingAs($this->user);
    get(route('table.task'))
        ->assertOk()
        ->assertSeeLivewire(TaskTable::class);
});

it('see current user task in table component', function () {
    // Arrange
    $task = Contract::factory()->create();

    // Act & Assert
    Livewire::actingAs(User::find($task->user_id))
        ->test(ContractTable::class)
        ->assertSee($task->name)
        ->assertSee($task->customer->name)
        ->assertSee($task->supplier->name)
        ->assertSee($task->signed_at->toDateString())
        ->assertSee($task->price_per_hour)
        ->assertOk();
});

it('table component dispatch event on edit button click', function () {
    // Arrange
    $task = Contract::factory()->create();

    // Act & Assert
    Livewire::actingAs(User::find($task->user_id))
        ->test(ContractTable::class)
        ->call('edit', $task->id)
        ->assertDispatched('open-update-form-modal')
        ->assertOk();
});

it('form component listen for event and open modal with task data', function () {
    // Arrange
    $task = Task::factory()
        ->recycle($this->user)
        ->create();

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(TaskForm::class)
        ->dispatch('open-update-form-modal', $task->id)
        ->assertSet('isModalOpen', true)
        ->assertSet('modelId', $task->id)
        ->assertViewHas('contract_id', $task->contract_id)
        ->assertViewHas('active', $task->active)
        ->assertViewHas('name', $task->name)
        ->assertViewHas('url', $task->url)
        ->assertViewHas('note', $task->note)
        ->assertOk();
});

it('form component update task successfully', function () {
    // Arrange
    $task = Task::factory()
        ->recycle($this->user)
        ->create(['active' => true]);
    $newContract = Contract::factory()
        ->recycle($this->user)
        ->create();

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(TaskForm::class)
        ->set('modelId', $task->id)
        ->call('setDataForUpdate')
        ->set('contract_id', $newContract->id)
        ->set('name', 'new test tame')
        ->set('url', 'new test url')
        ->set('note', 'new test note')
        ->set('active', false)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertOk();
    $this->assertDatabaseCount(Task::class, 1);
    $this->assertDatabaseHas(Task::class, [
        'id' => $task->id,
        'contract_id' => $newContract->id,
        'name' => 'new test tame',
        'url' => 'new test url',
        'note' => 'new test note',
        'active' => false,
    ]);
});

it('form component create task successfully', function () {
    $contract = Contract::factory()
        ->recycle($this->user)
        ->create();

    // Arrange & Act & Assert
    Livewire::actingAs($this->user)
        ->test(TaskForm::class)
        ->call('openCreateModal')
        ->set('contract_id', $contract->id)
        ->set('name', 'new test tame')
        ->set('url', 'new test url')
        ->set('note', 'new test note')
        ->set('active', true)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertOk();
    $this->assertDatabaseCount(Task::class, 1);
    $this->assertDatabaseHas(Task::class, [
        'contract_id' => $contract->id,
        'name' => 'new test tame',
        'url' => 'new test url',
        'note' => 'new test note',
        'active' => true,
    ]);
});

it('table component can deactivate task', function () {
    // Arrange
    $task = Task::factory()
        ->recycle($this->user)
        ->create(['active' => true]);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(TaskTable::class)
        ->call('selectPage', true)
        ->call('executeAction', 'deactivate')
        ->assertOk();
    $this->assertDatabaseHas(Task::class, [
        'id' => $task->id,
        'active' => false,
    ]);
});

it('table component can activate task', function () {
    // Arrange
    $task = Task::factory()
        ->recycle($this->user)
        ->create(['active' => false]);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(TaskTable::class)
        ->call('selectPage', true)
        ->call('executeAction', 'activate')
        ->assertOk();
    $this->assertDatabaseHas(Task::class, [
        'id' => $task->id,
        'active' => true,
    ]);
});

it('table component can delete task successfully', function () {
    // Arrange
    Task::factory()
        ->recycle($this->user)
        ->create();

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(TaskTable::class)
        ->call('selectPage', true)
        ->call('executeAction', 'delete')
        ->assertOk();
    $this->assertDatabaseCount(Task::class, 0);
});

it('table component see task hours', function () {
    // Arrange
    $task = Task::factory()
        ->recycle($this->user)
        ->create(['active' => false]);
    TaskHour::factory()
        ->recycle($this->user)
        ->create([
            'task_id' => $task->id,
            'hours' => 999999.99
        ]);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(TaskTable::class)
        ->assertSee($task->name)
        ->assertSee(999999.99)
        ->assertOk();
});

it('table component see task name url', function () {
    // Arrange
    $task = Task::factory()
        ->recycle($this->user)
        ->create([
        'url' => 'test:://phpunit.test',
        'active' => false,
    ]);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(TaskTable::class)
        ->assertSee($task->url)
        ->assertOk();
});

describe('authorization & visibility 👀', function () {
    it('page is secured by auth middleware', function () {
        // Arrange & Act & Assert
        get(route('table.task'))
            ->assertRedirectToRoute('login');
    });

    it('table display only current user tasks', function () {
        // Arrange
        $currentUser = $this->user;
        $anotherUser = User::factory()->create();
        $currentUserTask = Task::factory()
            ->recycle($currentUser)
            ->create();
        $anotherUserTask = Task::factory()
            ->recycle($anotherUser)
            ->create();

        // Act & Assert
        Livewire::actingAs($currentUser)
            ->test(TaskTable::class)
            ->assertSee($currentUserTask->name)
            ->assertDontSee($anotherUserTask->name);
    });

    it('forbidden to delete task because of delete policy', function () {
        // Arrange
        $this->partialMock(TaskPolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('delete')
                ->andReturnFalse();
        });
        Task::factory()
            ->recycle($this->user)
            ->create();

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(TaskTable::class)
            ->call('selectPage', true)
            ->call('executeAction', 'delete')
            ->assertForbidden();
    });

    it('forbidden to edit task because of update policy', function () {
        // Arrange
        $this->partialMock(TaskPolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
                ->andReturnFalse();
        });
        $task = Task::factory()
            ->recycle($this->user)
            ->create();

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(TaskForm::class)
            ->set('modelId', $task->id)
            ->call('openUpdateModal', true)
            ->assertForbidden();
    });

    it('forbidden to update task because of update policy', function () {
        // Arrange
        $this->partialMock(TaskPolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
                ->andReturnFalse();
        });
        $task = Task::factory()
            ->recycle($this->user)
            ->create();

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(TaskForm::class)
            ->set('modelId', $task->id)
            ->call('submit', true)
            ->assertForbidden();
    });

    it('forbidden to activate task because of update policy', function () {
        // Arrange
        $this->partialMock(TaskPolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
                ->andReturnFalse();
        });
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'active' => false,
        ]);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(TaskTable::class)
            ->call('selectPage', true)
            ->call('executeAction', 'activate')
            ->assertForbidden();
        $this->assertDatabaseHas(Task::class, [
            'id' => $task->id,
            'active' => false,
        ]);
    });

    it('forbidden to deactivate task because of update policy', function () {
        // Arrange
        $this->partialMock(TaskPolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
                ->andReturnFalse();
        });
        $task = Task::factory()
            ->recycle($this->user)
            ->create(['active' => true]);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(TaskTable::class)
            ->call('selectPage', true)
            ->call('executeAction', 'deactivate')
            ->assertForbidden();
        $this->assertDatabaseHas(Task::class, [
            'id' => $task->id,
            'active' => true,
        ]);
    });
});
