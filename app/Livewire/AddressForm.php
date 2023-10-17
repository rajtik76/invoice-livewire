<?php

namespace App\Livewire;

use App\Enums\CountryEnum;
use App\Models\Address;
use App\Traits\HasFormModalControl;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Rule;
use Livewire\Component;
use View;

class AddressForm extends Component
{
    use HasFormModalControl;

    public int $address;
    #[Rule(['required', 'string', 'max:255'])]
    public string|null $street = null;
    #[Rule(['required', 'string', 'max:255'])]
    public string|null $city = null;
    #[Rule(['required', 'string', 'max:255'])]
    public string|null $zip = null;
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
        $address = $this->getAddressModel();

        if (auth()->user()->cannot('update', $address)) {
            abort(403);
        }

        $this->street = $address->street;
        $this->city = $address->city;
        $this->zip = $address->zip;
        $this->country = $address->country;
    }

    public function updateAddress(): void
    {
        $validated = $this->validate();
        $address = $this->getAddressModel();

        if (auth()->user()->cannot('update', $address)) {
            abort(403);
        }

        $address->fill($validated);
        $address->save();

        $this->afterUpdate();
    }

    public function createAddress(): void
    {
        $validated = $this->validate();
        $address = auth()->user()->addresses()->create($validated);

        $this->resetModelData();
        $this->afterUpdate();
    }

    public function submit(): void
    {
        if ($this->modelId) {
            $this->updateAddress();
        } else {
            $this->createAddress();
        }
    }

    private function getAddressModel(): Address
    {
        return Address::findOrFail($this->modelId);
    }

    private function afterUpdate(): void
    {
        $this->dispatch('model-updated');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.address-form');
    }
}
