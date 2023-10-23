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
}
