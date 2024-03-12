<?php

use App\Livewire\Form\ReportForm;
use App\Livewire\Table\ReportTable;
use App\Models\Contract;
use App\Models\Report;
use App\Models\Task;
use App\Models\TaskHour;
use App\Models\User;
use App\Policies\ReportPolicy;
use Livewire\Livewire;
use Mockery\MockInterface;

use function Pest\Laravel\get;

beforeEach(function () {
    $this->user = User::factory()->create();
});

function getReport(User $user, array $attributes = []): Report
{
    return Report::factory()
        ->recycle($user)
        ->create($attributes);
}

it('report table page exists', function () {
    // Arrange & Act & Assert
    $this->actingAs($this->user);
    get(route('table.report'))
        ->assertOk();
});

it('see ReportTable component', function () {
    // Arrange & Act & Assert
    $this->actingAs($this->user);
    get(route('table.report'))
        ->assertOk()
        ->assertSeeLivewire(ReportTable::class);
});

it('see current user report in table component', function () {
    // Arrange
    $report = getReport($this->user);

    //  & Act & Assert
    Livewire::actingAs($this->user)
        ->test(ReportTable::class)
        ->assertOk()
        ->assertSee($report->contract->name);
});

it('table component dispatch event on edit button click', function () {
    // Arrange
    $report = getReport($this->user);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(ReportTable::class)
        ->call('edit', $report->id)
        ->assertOk()
        ->assertDispatched('open-update-form-modal');
});

it('form component listen for event and open modal with report data', function () {
    // Arrange
    $report = getReport($this->user);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(ReportForm::class)
        ->dispatch('open-update-form-modal', $report->id)
        ->assertOk()
        ->assertSet('isModalOpen', true)
        ->assertSet('modelId', $report->id)
        ->assertViewHas('contract_id', $report->contract_id)
        ->assertViewHas('year', $report->year)
        ->assertViewHas('month', $report->month);
});

it('form component update report successfully', function () {
    // Arrange
    $report = getReport($this->user, ['year' => 2000, 'month' => 1]);
    $newContract = Contract::factory()
        ->recycle($this->user)
        ->create();

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(ReportForm::class)
        ->set('modelId', $report->id)
        ->call('setDataForUpdate')
        ->set('contract_id', $newContract->id)
        ->set('year', 1900)
        ->set('month', 12)
        ->call('submit')
        ->assertOk()
        ->assertHasNoErrors();
    $this->assertDatabaseCount(Report::class, 1);
    $this->assertDatabaseHas(Report::class, [
        'id' => $report->id,
        'contract_id' => $newContract->id,
        'year' => 1900,
        'month' => 12,
    ]);
});

it('form component create report successfully', function () {
    $contract = Contract::factory()
        ->recycle($this->user)
        ->create();

    // Arrange & Act & Assert
    Livewire::actingAs($this->user)
        ->test(ReportForm::class)
        ->call('openCreateModal')
        ->set('contract_id', $contract->id)
        ->set('year', 1999)
        ->set('month', 6)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertOk();
    $this->assertDatabaseCount(Report::class, 1);
    $this->assertDatabaseHas(Report::class, [
        'contract_id' => $contract->id,
        'year' => 1999,
        'month' => 6,
    ]);
});

it('table component can delete report successfully', function () {
    // Arrange
    getReport($this->user);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(ReportTable::class)
        ->call('selectPage', true)
        ->call('executeAction', 'delete')
        ->assertOk();
    $this->assertDatabaseCount(Report::class, 0);
});

it('can download report', function () {
    // Arrange
    $report = getReport($this->user);

    // Act & Assert
    Livewire::actingAs($this->user)
        ->test(ReportTable::class)
        ->call('download', $report->id)
        ->assertOk()
        ->assertFileDownloaded("report-{$report->contract->name}-{$report->year}".sprintf('%02d', $report->month).'.pdf');
});

describe('authorization & visibility 👀', function () {
    it('page is secured by auth middleware', function () {
        // Arrange & Act & Assert
        get(route('table.report'))
            ->assertRedirectToRoute('login');
    });

    it('table display only current user report', function () {
        // Arrange
        $currentUserReport = getReport($this->user);
        $anotherUser = User::factory()->create();
        $anotherUserReport = getReport($anotherUser);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(ReportTable::class)
            ->assertSee($currentUserReport->contract->name)
            ->assertDontSee($anotherUserReport->contract->name);
    });

    it('display correct report hours', function () {
        // Arrange
        Report::factory()
            ->recycle($this->user)
            ->for(Contract::factory()
                ->has(Task::factory()
                    ->has(TaskHour::factory()->state(['hours' => 100.1]))
                )
            )
            ->create();

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(ReportTable::class)
            ->assertSeeText(1001.00);
    });

    it('forbidden to delete because of delete policy', function () {
        // Arrange
        $this->partialMock(ReportPolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('delete')
                ->andReturnFalse();
        });
        getReport($this->user);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(ReportTable::class)
            ->call('selectPage', true)
            ->call('executeAction', 'delete')
            ->assertForbidden();
        $this->assertDatabaseCount(Report::class, 1);
    });

    it('forbidden to open form because of update policy', function () {
        // Arrange
        $this->partialMock(ReportPolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
                ->andReturnFalse();
        });
        $task = getReport($this->user);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(ReportForm::class)
            ->set('modelId', $task->id)
            ->call('openUpdateModal', true)
            ->assertForbidden();
    });

    it('forbidden to update because of update policy', function () {
        // Arrange
        $this->partialMock(ReportPolicy::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
                ->andReturnFalse();
        });
        $task = getReport($this->user);

        // Act & Assert
        Livewire::actingAs($this->user)
            ->test(ReportForm::class)
            ->set('modelId', $task->id)
            ->call('submit', true)
            ->assertForbidden();
    });
});
