<?php

namespace App\Livewire\Form;

use App\Traits\HasFormModalControl;

abstract class Component extends \Livewire\Component
{
    use HasFormModalControl;

    public function boot(): void
    {
        $this->resetErrorBag();
    }

    public function submit()
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
