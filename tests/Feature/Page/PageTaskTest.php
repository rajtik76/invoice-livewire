<?php

use App\Livewire\Table\ContractTable;
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
    get(route('table.task'))
        ->assertOk();
});

it('page is secured by auth middleware', function () {
    // Act & Assert
    get(route('table.task'))
        ->assertRedirectToRoute('login');
});

it('see task table livewire component', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    $this->actingAs($user);
    get(route('table.task'))
        ->assertOk()
        ->assertSeeLivewire(TaskTable::class);
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
    $task = Task::factory()->create(['user_id' => $user->id]);

    // Act & Assert
    Livewire::actingAs($user)
        ->test('form.task-form')
        ->dispatch('open-update-form-modal', $task->id)
        ->assertSet('isModalOpen', true)
        ->assertSet('modelId', $task->id)
        ->assertViewHas('contract_id', $task->contract_id)
        ->assertViewHas('active', $task->active)
        ->assertViewHas('name', $task->name)
        ->assertViewHas('url', $task->url)
        ->assertViewHas('note', $task->note);
});

it('can deactivate task', function () {
    // Arrange
    $user = User::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $user->id,
        'active' => true
    ]);

    // Act & Assert
    Livewire::actingAs($user)
        ->test(TaskTable::class)
        ->call('selectPage', true)
        ->call('executeAction', 'deactivate');
    $this->assertDatabaseHas(Task::class, [
        'id' => $task->id,
        'active' => false,
    ]);
});

it('can activate task', function () {
    // Arrange
    $user = User::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $user->id,
        'active' => false
    ]);

    // Act & Assert
    Livewire::actingAs($user)
        ->test(TaskTable::class)
        ->call('selectPage', true)
        ->call('executeAction', 'activate');
    $this->assertDatabaseHas(Task::class, [
        'id' => $task->id,
        'active' => true,
    ]);
});

it('it sees task hours', function () {
    // Arrange
    $user = User::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $user->id,
        'active' => false
    ]);
    TaskHour::factory()->create([
        'user_id' => $user->id,
        'task_id' => $task->id,
        'hours' => 999999.99
    ]);

    // Act & Assert
    Livewire::actingAs($user)
        ->test(TaskTable::class)
        ->assertSee($task->name)
        ->assertSee(999999.99);
});

it('see task name url', function () {
    // Arrange
    $user = User::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $user->id,
        'url' => 'test:://phpunit.test',
        'active' => false
    ]);

    // Act & Assert
    Livewire::actingAs($user)
        ->test(TaskTable::class)
        ->assertSee($task->url);
});

it('can delete', function() {
    // Arrange
    $user = User::factory()->create();
    Task::factory()->create(['user_id' => $user->id]);

    // Act & Assert
    Livewire::actingAs($user)
        ->test(TaskTable::class)
        ->call('selectPage', true)
        ->call('executeAction', 'delete');
    $this->assertDatabaseCount(Task::class, 0);
});
