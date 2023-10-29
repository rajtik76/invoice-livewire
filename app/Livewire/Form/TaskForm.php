<?php

declare(strict_types=1);

namespace App\Livewire\Form;

use App\Models\Contract;
use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;

class TaskForm extends BaseForm
{
    public ?int $contract_id = null;

    public ?int $active = null;

    public ?string $name = null;

    public ?string $url = null;

    public ?string $note = null;

    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'contract_id' => ['required', Rule::exists(Contract::class, 'id')->where('user_id', auth()->id())],
            'active' => ['boolean', 'nullable'],
            'name' => ['required', 'max:255'],
            'url' => ['nullable', 'max:255'],
            'note' => ['nullable', 'max:255'],
        ];
    }

    public function setDataForUpdate(): void
    {
        $model = $this->getModel();

        $this->contract_id = $model->contract_id;
        $this->active = intval($model->active);
        $this->name = $model->name;
        $this->url = $model->url;
        $this->note = $model->note;
    }

    public function setDataForStore(): void
    {
        $this->contract_id = null;
        $this->active = 1;
        $this->name = null;
        $this->url = null;
        $this->note = null;
    }

    public function render(): View
    {
        return view('livewire.form.task-form');
    }

    protected function prepareForValidation($attributes)
    {
        $attributes['active'] = (bool) $attributes['active'];

        return $attributes;
    }

    protected function createModel(): void
    {
        auth()->user()->tasks()->create($this->validate());
    }

    protected function getModel(): Task
    {
        return Task::findOrFail($this->modelId);
    }
}
