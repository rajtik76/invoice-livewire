<?php

namespace App\Livewire\Form;

use App\Models\Customer;

class CustomerForm extends Component
{
    public ?int $address_id = null;

    public ?string $name = null;

    public ?string $vat_number = null;

    public ?string $registration_number = null;

    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'address_id' => ['required', 'exists:addresses,id'],
            'name' => ['required', 'max:255'],
            'vat_number' => ['required', 'max:255'],
            'registration_number' => ['max:255'],
        ];
    }

    public function fetchModelData(): void
    {
        $model = $this->getModel();

        $this->address_id = $model->address_id;
        $this->name = $model->name;
        $this->registration_number = $model->registration_number;
        $this->vat_number = $model->vat_number;
    }

    public function resetModelData(): void
    {
        $this->address_id = null;
        $this->name = null;
        $this->registration_number = null;
        $this->vat_number = null;
    }

    public function render()
    {
        return view('livewire.customer-form');
    }

    protected function updateModel(): void
    {
        $customer = $this->getModel();

        if (! $this->authorize('update', $customer)) {
            abort(403);
        }

        $customer->update($this->validate());

        $this->dispatch('model-updated');
    }

    protected function createModel(): void
    {
        if (! $this->authorize('create', Customer::class)) {
            abort(403);
        }

        auth()->user()->customers()->create($this->validate());
        $this->resetModelData();

        $this->dispatch('model-updated');
    }

    private function getModel(): Customer
    {
        return Customer::findOrFail($this->modelId);
    }
}
