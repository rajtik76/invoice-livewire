<?php

namespace App\Livewire\Form;

use App\Models\Address;
use App\Models\BankAccount;
use App\Models\Supplier;
use Illuminate\Validation\Rule;

class SupplierForm extends Component
{
    public ?int $address_id = null;
    public ?int $bank_account_id = null;
    public ?string $name = null;
    public ?string $vat_number = null;
    public ?string $registration_number = null;
    public ?string $email = null;
    public ?string $phone = null;

    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'address_id' => ['required', Rule::exists(Address::class, 'id')->where('user_id', auth()->id())],
            'bank_account_id' => ['required', Rule::exists(BankAccount::class, 'id')->where('user_id', auth()->id())],
            'name' => ['required', 'max:255'],
            'vat_number' => ['required', 'max:255'],
            'registration_number' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'max:255'],
        ];
    }

    public function fetchModelData(): void
    {
        $model = $this->getModel();

        $this->address_id = $model->address_id;
        $this->bank_account_id = $model->bank_account_id;
        $this->name = $model->name;
        $this->registration_number = $model->registration_number;
        $this->vat_number = $model->vat_number;
        $this->phone = $model->phone;
        $this->email = $model->email;
    }

    public function resetModelData(): void
    {
        $this->address_id = null;
        $this->bank_account_id = null;
        $this->name = null;
        $this->registration_number = null;
        $this->vat_number = null;
        $this->phone = null;
        $this->email = null;
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
        return view('livewire.supplier-form');
    }

    private function updateModel()
    {
        $supplier = $this->getModel();

        if (!$this->authorize('update', $supplier)) {
            abort(403);
        }

        $supplier->update($this->validate());

        $this->dispatch('model-updated');
    }

    private function createModel()
    {
        if (!$this->authorize('create', Supplier::class)) {
            abort(403);
        }

        auth()->user()->suppliers()->create($this->validate());
        $this->resetModelData();

        $this->dispatch('model-updated');
    }

    private function getModel(): Supplier
    {
        return Supplier::findOrFail($this->modelId);
    }
}
