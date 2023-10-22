<?php

namespace App\Traits;

use Livewire\Attributes\On;

trait HasTableRefreshListener
{
    #[On('model-updated')]
    public function refreshData(): void
    {
        $this->dispatch('refreshLivewireTable');
    }
}
