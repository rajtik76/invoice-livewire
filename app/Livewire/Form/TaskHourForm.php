<?php

declare(strict_types=1);

namespace App\Livewire\Form;

use App\Models\Task;
use App\Models\TaskHour;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Override;

class TaskHourForm extends BaseForm
{
    public ?int $task = null;

    public ?int $task_id = null;

    public ?string $date = null;

    public string|float|null $hours = null;

    public ?string $comment = null;

    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'task_id' => ['required', Rule::exists(Task::class, 'id')->where('user_id', auth()->id())],
            'date' => ['required', 'date'],
            'hours' => ['required', 'numeric', 'min:0.1'],
            'comment' => ['nullable'],
        ];
    }

    public function setDataForUpdate(): void
    {
        $model = $this->getModel();

        $this->task_id = $model->task_id;
        $this->date = $model->date->toDateString();
        $this->hours = $model->hours;
        $this->comment = $model->comment;
    }

    public function setDataForStore(): void
    {
        $this->task_id = $this->task;
        $this->date = now()->toDateString();
        $this->hours = null;
        $this->comment = null;
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

    #[Override]
    protected function updateModelAction(): void
    {
        // get model
        $model = $this->getModel();

        // authorize user action
        $this->authorize('update', $model);

        $validated = $this->validate();
        $validated['comment'] = empty($validated['comment']) ? null : $validated['comment'];

        // update model data
        $model->update($validated);
    }
}
