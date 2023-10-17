<?php

namespace App\Traits;

use Livewire\Attributes\On;

trait HasFormModalControl
{
    /**
     * ID of updated model
     *
     * @var int|null
     */
    public int|null $modelId = null;

    /**
     * Model is open flag
     *
     * @var bool
     */
    public bool $isModalOpen = false;

    #[On('open-update-form-modal')]
    public function openUpdateModal(int $modelId): void
    {
        $this->modelId = $modelId;
        $this->fetchModelData();
        $this->isModalOpen = true;
    }

    #[On('open-create-form-modal')]
    public function openCreateModal(): void
    {
        $this->modelId = null;
        $this->resetModelData();
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->dispatch('model-updated');
    }

    abstract public function fetchModelData(): void;
    abstract public function resetModelData(): void;
}
