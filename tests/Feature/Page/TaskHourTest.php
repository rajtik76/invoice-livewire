<?php

use App\Livewire\Form\TaskHourForm;
use App\Livewire\Table\TaskHourTable;
use App\Models\Task;
use App\Models\TaskHour;
use App\Models\User;
use App\Policies\TaskHourPolicy;
use Carbon\Carbon;
use Livewire\Livewire;
use Mockery\MockInterface;

use function Pest\Laravel\get;

beforeEach(function () {
    $this->user = User::factory()->create();
});

function getTaskHour(User $user, array $attributes = [])
{
    return TaskHour::factory()
        ->recycle($user)
        ->create($attributes);
}

it('taskHour table page exists', function () {
    // Arrange & Act & Assert
    $this->actingAs($this->user);
    get(route('table.taskHour'))
        ->assertOk();
});

it('see TaskHourTable component', function () {
    // Arrange & Act & Assert
    $this->actingAs($this->user);
    get(route('table.taskHour'))
        ->assertOk()
        ->assertSeeLivewire(TaskHourTable::class);
});

it('see current user taskHour in table component', function () {
    // Arrange
    $taskHour = getTaskHour($this->user);

    //  & Act & Assert
    Livewire::actingAs($this->user)
        ->test(TaskHourTable::class)
        ->assertOk()
        ->assertSee($taskHour->task_id)
        ->assertSee($taskHour->date->toDateString())
        ->assertSee($taskHour->hours);
});

it('table component dispatch event on edit button click', function () {
    // Arrange
    $taskHour = getTaskHour($this->user);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(TaskHourTable::class)
        ->call('edit', $taskHour->id)
        ->assertOk()
        ->assertDispatched('open-update-form-modal');
});

it('form component listen for event and open modal with taskHour data', function () {
    // Arrange
    $taskHour = getTaskHour($this->user);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(TaskHourForm::class)
        ->dispatch('open-update-form-modal', $taskHour->id)
        ->assertOk()
        ->assertSet('isModalOpen', true)
        ->assertSet('modelId', $taskHour->id)
        ->assertViewHas('task_id', $taskHour->task_id)
        ->assertViewHas('date', $taskHour->date->toDateString())
        ->assertViewHas('hours', $taskHour->hours);
});

it('form component update taskHour successfully', function () {
    // Arrange
    $taskHour = getTaskHour($this->user);
    $newTask = Task::factory()
        ->recycle($this->user)
        ->create();

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(TaskHourForm::class)
        ->set('modelId', $taskHour->id)
        ->call('setDataForUpdate')
        ->set('task_id', $newTask->id)
        ->set('date', '2020-05-05')
        ->set('hours', 999.99)
        ->call('submit')
        ->assertOk()
        ->assertHasNoErrors();
    $this->assertDatabaseCount(TaskHour::class, 1);
    $this->assertDatabaseHas(TaskHour::class, [
        'id' => $taskHour->id,
        'task_id' => $newTask->id,
        'date' => new Carbon('2020-05-05'),
        'hours' => 999.99,
    ]);
});

it('form component create taskHour successfully', function () {
    $task = Task::factory()
        ->recycle($this->user)
        ->create();

    // Arrange & Act & Assert
    Livewire::actingAs($this->user)
        ->test(TaskHourForm::class)
        ->call('openCreateModal')
        ->set('task_id', $task->id)
        ->set('date', '2020-02-02')
        ->set('hours', 888.88)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertOk();
    $this->assertDatabaseCount(TaskHour::class, 1);
    $this->assertDatabaseHas(TaskHour::class, [
        'user_id' => $this->user->id,
        'task_id' => $task->id,
        'date' => new Carbon('2020-02-02'),
        'hours' => 888.88,
    ]);
});

it('table component can delete taskHour successfully', function () {
    // Arrange
    getTaskHour($this->user);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(TaskHourTable::class)
        ->call('selectPage', true)
        ->call('executeAction', 'delete')
        ->assertOk();
    $this->assertDatabaseCount(TaskHour::class, 0);
});

describe('authorization & visibility 👀', function () {
    it('page is secured by auth middleware', function () {
        // Arrange & Act & Assert
        get(route('table.taskHour'))
            ->assertRedirectToRoute('login');
    });

    it('table display only current user taskHour', function () {
        // Arrange
        $currentUserTaskHour = getTaskHour($this->user, ['hours' => 111]);
        $anotherUser = User::factory()->create();
        $anotherUserTaskHour = getTaskHour($anotherUser, ['hours' => 222]);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(TaskHourTable::class)
            ->assertSee($currentUserTaskHour->hours)
            ->assertDontSee($anotherUserTaskHour->hours);
    });

    it('forbidden to delete because of delete policy', function () {
        // Arrange
        $this->partialMock(TaskHourPolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('delete')
                ->andReturnFalse();
        });
        getTaskHour($this->user);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(TaskHourTable::class)
            ->call('selectPage', true)
            ->call('executeAction', 'delete')
            ->assertForbidden();
        $this->assertDatabaseCount(TaskHour::class, 1);
    });

    it('forbidden to open form because of update policy', function () {
        // Arrange
        $this->partialMock(TaskHourPolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
                ->andReturnFalse();
        });
        $taskHour = getTaskHour($this->user);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(TaskHourForm::class)
            ->set('modelId', $taskHour->id)
            ->call('openUpdateModal', true)
            ->assertForbidden();
    });

    it('forbidden to update because of update policy', function () {
        // Arrange
        $this->partialMock(TaskHourPolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
                ->andReturnFalse();
        });
        $taskHour = getTaskHour($this->user);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(TaskHourForm::class)
            ->set('modelId', $taskHour->id)
            ->call('submit', true)
            ->assertForbidden();
    });
});
