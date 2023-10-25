<?php
declare(strict_types=1);

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
    public null|string|CountryEnum $country = null;

    public function setDataForCreate(): void
    {
        $this->street = null;
        $this->city = null;
        $this->zip = null;
        $this->country = null;
    }

    public function setDataForUpdate(): void
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

    protected function createModel(): void
    {
        $validated = $this->validate();
        auth()->user()->addresses()->create($validated);
    }

    protected function getModel(): Address
    {
        return Address::findOrFail($this->modelId);
    }

    public function render(): View
    {
        return view('livewire.form.address-form');
    }
}
