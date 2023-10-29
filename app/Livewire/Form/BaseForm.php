<?php

declare(strict_types=1);

namespace App\Livewire\Form;

use App\Livewire\Table\BaseTable;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Livewire\Component;

abstract class BaseForm extends Component
{
    /**
     * Model Id
     */
    public ?int $modelId = null;

    /**
     * Is modal window open flag
     */
    public bool $isModalOpen = false;

    /**
     * Reset error bag on boot.
     * This solving the problem with persistent validation errors (if any) on modal close and open.
     */
    public function boot(): void
    {
        // reset errors on open form
        $this->resetErrorBag();
    }

    /**
     * Listen for open-update-form-modal event.
     * This event open form modal window in edit state.
     */
    #[On('open-update-form-modal')]
    public function openUpdateModal(int $modelId): void
    {
        // set model id
        $this->modelId = $modelId;

        // authorize if user can update model
        $this->authorize('update', $this->getModel());

        // fetch model data
        $this->setDataForUpdate();

        // open modal window
        $this->isModalOpen = true;
    }

    /**
     * Listen for open-create-form-modal.
     * This event open form modal window in create state.
     */
    #[On('open-create-form-modal')]
    public function openCreateModal(): void
    {
        // reset model id to null
        $this->modelId = null;

        // reset model data to default values
        $this->setDataForStore();

        // open modal window
        $this->isModalOpen = true;
    }

    /**
     * Close form modal
     */
    public function closeModal(): void
    {
        // close modal
        $this->isModalOpen = false;

        // dispatch event to update BaseTable data
        $this->dispatch('model-updated')->to(BaseTable::class);
    }

    /**
     * Form submission. Update when modelId exists otherwise create.
     */
    public function submit(): void
    {
        if ($this->modelId) {
            // update model
            $this->updateModelAction();
        } else {
            // create model
            $this->createModelAction();
        }

        // dispatch event
        $this->dispatch('model-updated');
    }

    /**
     * Update model data
     */
    protected function updateModelAction(): void
    {
        // get model
        $model = $this->getModel();

        // authorize user action
        $this->authorize('update', $model);

        // update model data
        $model->update($this->validate());
    }

    /**
     * Create new model from submitted data
     */
    protected function createModelAction(): void
    {
        // create new model
        $this->createModel();

        // sets the data to the necessary values to create a new model
        $this->setDataForStore();
    }

    /**
     * Fetch model data from DB
     */
    abstract protected function getModel(): Model;

    /**
     * This action is responsible for creating the new model
     */
    abstract protected function createModel(): void;

    /**
     * Get model DB data and store them in properties
     */
    abstract public function setDataForUpdate(): void;

    /**
     * Sets the data to the necessary values to create a new model
     */
    abstract public function setDataForStore(): void;
}
