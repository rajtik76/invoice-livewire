<?php

use App\Livewire\Form\TaskHourForm;
use App\Livewire\Table\ContractTable;
use App\Livewire\Table\TaskHourTable;
use App\Livewire\Table\TaskTable;
use App\Models\Contract;
use App\Models\Task;
use App\Models\TaskHour;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\get;

it('page exists', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    $this->actingAs($user);
    get(route('table.taskHour'))
        ->assertOk();
});

it('page is secured by auth middleware', function () {
    // Act & Assert
    get(route('table.taskHour'))
        ->assertRedirectToRoute('login');
});

it('see task hour table livewire component', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    $this->actingAs($user);
    get(route('table.taskHour'))
        ->assertOk()
        ->assertSeeLivewire(TaskHourTable::class);
});

it('see task in table', function () {
    // Arrange
    $task = Contract::factory()->create();

    // Act & Assert
    Livewire::actingAs(User::find($task->user_id))
        ->test(ContractTable::class)
        ->assertSee($task->name)
        ->assertSee($task->customer->name)
        ->assertSee($task->supplier->name)
        ->assertSee($task->signed_at->toDateString())
        ->assertSee($task->price_per_hour);
});

it('edit button dispatch event', function () {
    // Arrange
    $task = Contract::factory()->create();

    // Act & Assert
    Livewire::actingAs(User::find($task->user_id))
        ->test(ContractTable::class)
        ->call('edit', $task->id)
        ->assertDispatched('open-update-form-modal');
});

it('edit form listen for event and open modal with data', function () {
    // Arrange
    $user = User::factory()->create();
    $taskHour = TaskHour::factory()->create(['user_id' => $user->id]);

    // Act & Assert
    Livewire::actingAs($user)
        ->test('form.taskHour-form')
        ->dispatch('open-update-form-modal', $taskHour->id)
        ->assertSet('isModalOpen', true)
        ->assertSet('modelId', $taskHour->id)
        ->assertViewHas('task_id', $taskHour->task_id)
        ->assertViewHas('date', $taskHour->date->toDateString())
        ->assertViewHas('hours', $taskHour->hours);
});

it('can delete', function () {
    // Arrange
    $user = User::factory()->create();
    TaskHour::factory()->create(['user_id' => $user->id]);

    // Act & Assert
    Livewire::actingAs($user)
        ->test(TaskHourTable::class)
        ->call('selectPage', true)
        ->call('executeAction', 'delete');
    $this->assertDatabaseCount(TaskHour::class, 0);
});

it('task select is disabled when task is provided', function () {
    // Arrange
    $user = User::factory()->create();
    $taskHour = TaskHour::factory()->create(['id' => 100, 'user_id' => $user->id]);

    // Act & Assert
    Livewire::actingAs($user)
        ->test(TaskHourForm::class)
        ->set('task', $taskHour->task_id)
        ->call('openCreateModal')
        ->assertSeeHtml("disabled=\"{$taskHour->task_id}\"")
    ;
});
