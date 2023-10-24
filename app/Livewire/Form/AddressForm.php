<?php

namespace App\Livewire\Form;

use App\Enums\CountryEnum;
use App\Models\Address;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Rule;

class AddressForm extends BaseForm
{
    public int $address;

    #[Rule(['required', 'string', 'max:255'])]
    public ?string $street = null;

    #[Rule(['required', 'string', 'max:255'])]
    public ?string $city = null;

    #[Rule(['required', 'string', 'max:255'])]
    public ?string $zip = null;

    #[Rule(['required', new Enum(CountryEnum::class)])]
    public string|CountryEnum $country = CountryEnum::Germany;

    public function resetModelData(): void
    {
        $this->street = null;
        $this->city = null;
        $this->zip = null;
    }

    public function fetchModelData(): void
    {
        $address = $this->getModel();

        if (auth()->user()->cannot('update', $address)) {
            abort(403);
        }

        $this->street = $address->street;
        $this->city = $address->city;
        $this->zip = $address->zip;
        $this->country = $address->country;
    }

    protected function updateModel(): void
    {
        $validated = $this->validate();
        $address = $this->getModel();

        if (auth()->user()->cannot('update', $address)) {
            abort(403);
        }

        $address->fill($validated);
        $address->save();

        $this->afterUpdate();
    }

    protected function createModel(): void
    {
        $validated = $this->validate();
        auth()->user()->addresses()->create($validated);

        $this->resetModelData();
        $this->afterUpdate();
    }

    private function getModel(): Address
    {
        return Address::findOrFail($this->modelId);
    }

    private function afterUpdate(): void
    {
        $this->dispatch('model-updated');
    }

    public function render(): View
    {
        return view('livewire.form.address-form');
    }
}
