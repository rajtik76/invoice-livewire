<?php

namespace App\Livewire\Form;

use App\Models\Address;
use App\Models\BankAccount;
use App\Models\Supplier;
use Illuminate\Validation\Rule;

class SupplierForm extends BaseForm
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

    public function render()
    {
        return view('livewire.form.supplier-form');
    }

    protected function createModel(): void
    {
        if (! $this->authorize('create', Supplier::class)) {
            abort(403);
        }

        auth()->user()->suppliers()->create($this->validate());
    }

    protected function getModel(): Supplier
    {
        return Supplier::findOrFail($this->modelId);
    }
}
