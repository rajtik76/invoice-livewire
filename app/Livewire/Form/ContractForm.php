<?php

namespace App\Livewire\Form;

use App\Enums\CurrencyEnum;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ContractForm extends BaseForm
{
    public ?int $customer_id = null;

    public ?int $supplier_id = null;

    public string|bool|null $active = null;

    public ?string $name = null;

    public ?string $signed_at = null;

    public string|float|null $price_per_hour = null;

    public ?string $currency = null;

    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['required', Rule::exists(Customer::class, 'id')->where('user_id', auth()->id())],
            'supplier_id' => ['required', Rule::exists(Supplier::class, 'id')->where('user_id', auth()->id())],
            'active' => ['boolean', 'nullable'],
            'name' => ['required', 'max:255'],
            'signed_at' => ['required', 'date'],
            'price_per_hour' => ['required', 'numeric', 'min:1'],
            'currency' => ['required', new Enum(CurrencyEnum::class)],
        ];
    }

    public function fetchModelData(): void
    {
        $model = $this->getModel();

        $this->customer_id = $model->customer_id;
        $this->supplier_id = $model->supplier_id;
        $this->active = $model->active;
        $this->name = $model->name;
        $this->signed_at = $model->signed_at->toDateString();
        $this->price_per_hour = $model->price_per_hour;
        $this->currency = $model->currency->value;
    }

    public function resetModelData(): void
    {
        $this->customer_id = null;
        $this->supplier_id = null;
        $this->active = true;
        $this->name = null;
        $this->signed_at = now()->toDateString();
        $this->price_per_hour = null;
        $this->currency = null;
    }

    public function render(): View
    {
        return view('livewire.form.contract-form');
    }

    protected function prepareForValidation($attributes)
    {
        $attributes['active'] = (bool) $attributes['active'];

        return $attributes;
    }

    protected function createModel(): void
    {
        $this->authorize('create', Supplier::class);

        auth()->user()->contracts()->create($this->validate());
    }

    protected function getModel(): Contract
    {
        return Contract::findOrFail($this->modelId);
    }
}
