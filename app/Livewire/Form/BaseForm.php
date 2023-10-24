<?php

namespace App\Livewire\Form;

use App\Traits\HasFormModalControl;
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
            $this->createModel();
        }
    }

    abstract protected function createModel(): void;

    abstract protected function updateModel(): void;
}
