<?php

use App\Filament\Resources\TaskResource\Pages\ListTasks;
use App\Models\Task;
use App\Models\User;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Factories\Sequence;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('can see all active tasks in order', function () {
    // Arrange
    $tasks = Task::factory()
        ->count(5)
        ->recycle($this->user)
        ->active()
        ->create()
        ->sortBy('created_at');

    // Act & Assert
    livewire(ListTasks::class)
        ->assertStatus(200)
        ->assertCanSeeTableRecords($tasks, true);
});

it('can see only active tasks by default', function () {
    // Arrange
    $activeTasks = Task::factory()
        ->count(3)
        ->recycle($this->user)
        ->active()
        ->create()
        ->sortBy('created_at');

    Task::factory()
        ->count(2)
        ->recycle($this->user)
        ->inactive()
        ->create();

    // Act & Assert
    livewire(ListTasks::class)
        ->assertStatus(200)
        ->assertCanSeeTableRecords($activeTasks, true);
});

it('can see also inactive tasks when active filter is turned off', function () {
    // Arrange
    $tasks = Task::factory()
        ->count(5)
        ->recycle($this->user)
        ->state(new Sequence(
            ['active' => true],
            ['active' => true],
            ['active' => true],
            ['active' => false],
            ['active' => false],
        ))
        ->create()
        ->sortBy('created_at');

    // Act & Assert
    livewire(ListTasks::class)
        ->filterTable('active', false)
        ->assertStatus(200)
        ->assertCanSeeTableRecords($tasks, true);
});

it('can see only current user tasks', function () {
    // Arrange
    $currentUserTasks = Task::factory()
        ->count(2)
        ->recycle($this->user)
        ->create();

    $anotherUsersTasks = Task::factory()
        ->count(3)
        ->create();

    // Act & Assert
    livewire(ListTasks::class)
        ->assertStatus(200)
        ->assertCanSeeTableRecords($currentUserTasks)
        ->assertCanNotSeeTableRecords($anotherUsersTasks);
});

it('can deactivate task', function () {
    // Arrange
    $task = Task::factory()
        ->recycle($this->user)
        ->create(['id' => 1000]);

    // Act & Assert
    livewire(ListTasks::class)
        ->callTableBulkAction('deactivate', [$task])
        ->assertStatus(200)
        ->assertCanNotSeeTableRecords([$task]); // Deactivated tasks can't be seen

    assertDatabaseHas('tasks', [
        'id' => $task->id,
        'active' => false,
    ]);
});

it('can delete task', function () {
    // Arrange
    $task = Task::factory()
        ->recycle($this->user)
        ->create(['id' => 1000]);

    // Act & Assert
    livewire(ListTasks::class)
        ->callTableBulkAction('delete', [$task])
        ->assertStatus(200);

    assertDatabaseCount('tasks', 0);
});
