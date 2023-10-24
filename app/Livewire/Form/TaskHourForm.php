<?php

namespace App\Livewire\Form;

use App\Models\Task;
use App\Models\TaskHour;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;

class TaskHourForm extends BaseForm
{
    public ?int $task = null;

    public ?int $task_id = null;

    public ?string $date = null;

    public string|float|null $hours = null;

    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'task_id' => ['required', Rule::exists(Task::class, 'id')->where('user_id', auth()->id())],
            'date' => ['required', 'date'],
            'hours' => ['required', 'numeric', 'min:0.1'],
        ];
    }

    public function fetchModelData(): void
    {
        $model = $this->getModel();

        $this->task_id = $model->task_id;
        $this->date = $model->date->toDateString();
        $this->hours = $model->hours;
    }

    public function resetModelData(): void
    {
        $this->task_id = $this->task;
        $this->date = now()->toDateString();
        $this->hours = null;
    }

    public function render(): View
    {
        return view('livewire.form.taskHour-form');
    }

    protected function createModel(): void
    {
        $this->authorize('create', Task::class);

        auth()->user()->taskHours()->create($this->validate());
    }

    protected function getModel(): TaskHour
    {
        return TaskHour::findOrFail($this->modelId);
    }
}
