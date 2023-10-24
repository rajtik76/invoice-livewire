<?php

namespace App\Livewire\Form;

use App\Models\Task;
use App\Traits\HasFormModalControl;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

abstract class BaseForm extends Component
{
    use HasFormModalControl;

    public function boot(): void
    {
        // reset errors on open form
        $this->resetErrorBag();
    }

    /**
     * Form submission. Update when modelId exists otherwise create.
     */
    public function submit(): void
    {
        if ($this->modelId) {
            $this->updateModel();
        } else {
            $this->createModelHook();
        }
    }

    protected function updateModel(): void
    {
        $task = $this->getModel();

        if (!$this->authorize('update', $task)) {
            abort(403);
        }

        $task->update($this->validate());

        $this->dispatch('model-updated');
    }

    protected function createModelHook(): void
    {
        // create model
        $this->createModel();
        // reset data
        $this->resetModelData();
        // dispatch model-updated event to refresh table data
        $this->dispatch('model-updated');
    }

    abstract protected function createModel(): void;

    abstract protected function getModel(): Model;
}
