<?php

namespace App\Livewire\Form;

use App\Models\Customer;

class CustomerForm extends BaseForm
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
        return view('livewire.form.customer-form');
    }

    protected function createModel(): void
    {
        $this->authorize('create', Customer::class);

        auth()->user()->customers()->create($this->validate());
    }

    protected function getModel(): Customer
    {
        return Customer::findOrFail($this->modelId);
    }
}
