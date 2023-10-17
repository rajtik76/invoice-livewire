<?php

namespace App\Traits;

use Livewire\Attributes\On;

trait HasFormModalListener
{
    #[On('model-updated')]
    public function refreshData(): void
    {
        $this->dispatch('refreshLivewireTable');
    }
}
