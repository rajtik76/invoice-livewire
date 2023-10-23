<?php

namespace App\Livewire\Form;

use App\Models\Address;
use App\Models\Customer;
use App\Traits\HasFormModalControl;
use Livewire\Attributes\Rule;
use Livewire\Component;

class CustomerForm extends Component
{
    use HasFormModalControl;

    #[Rule(['required', 'exists:addresses,id'])]
    public ?int $address_id = null;

    #[Rule(['required', 'max:255'])]
    public ?string $name = null;

    #[Rule(['required', 'max:255'])]
    public ?string $vat_number = null;

    #[Rule(['max:255'])]
    public ?string $registration_number = null;

    protected function getAddressOption(): array
    {
        return Address::currentUser()
            ->orderBy('country')
            ->orderBy('city')
            ->orderBy('street')
            ->get()
            ->keyBy('id')
            ->map(fn(Address $address) => "{$address->street}, {$address->zip} {$address->city}, {$address->country->countryName()}")
            ->all();
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

    public function submit()
    {
        if ($this->modelId) {
            $this->updateModel();
        } else {
            $this->createModel();
        }
    }

    public function render()
    {
        return view('livewire.customer-form');
    }

    private function updateModel()
    {
        $customer = $this->getModel();

        if (!$this->authorize('update', $customer)) {
            abort(403);
        }

        $customer->update($this->validate());

        $this->dispatch('model-updated');
    }

    private function createModel()
    {
        if (!$this->authorize('create', Customer::class)) {
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
