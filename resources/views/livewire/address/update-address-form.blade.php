<?php

use App\Enums\CountryEnum;
use App\Models\Address;
use App\Traits\HasFormModalControl;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Livewire\Volt\Component;
use function Livewire\Volt\{mount, rules, state};

new class extends Component {

    use HasFormModalControl;

    public int $address;
    #[\Livewire\Attributes\Rule(['required', 'string', 'max:255'])]
    public string|null $street = null;
    #[\Livewire\Attributes\Rule(['required', 'string', 'max:255'])]
    public string|null $city = null;
    #[\Livewire\Attributes\Rule(['required', 'string', 'max:255'])]
    public string|null $zip = null;
    #[\Livewire\Attributes\Rule(['required', new Enum(CountryEnum::class)])]
    public string|CountryEnum $country = CountryEnum::Germany;

    public function resetModelData(): void
    {
        $this->addressId = null;
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
        $this->addressId = $address->id;
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
}

?>

<div>
    @if($isModalOpen)
        <x-modal name="address-update-form" :show="true" :auto-close="false">
            <div class="p-4">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Address information
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Your customer or supplier address.
                        </p>
                    </header>

                    <form wire:submit.prevent="submit" class="mt-6 space-y-6">
                        <div>
                            <x-input-label for="street" value="Street"/>
                            <x-text-input wire:model="street" id="street" name="street" type="text"
                                          class="mt-1 block w-full"
                                          required autofocus/>
                            <x-input-error class="mt-2" :messages="$errors->get('street')"/>
                        </div>

                        <div class="grid grid-cols-2 gap-4 w-full">
                            <div>
                                <x-input-label for="city" value="City"/>
                                <x-text-input wire:model="city" id="city" name="city" type="text"
                                              class="mt-1 block w-full"
                                              required/>
                                <x-input-error class="mt-2" :messages="$errors->get('city')"/>
                            </div>

                            <div>
                                <x-input-label for="zip" value="ZIP"/>
                                <x-text-input wire:model="zip" id="zip" name="zip" type="text" class="mt-1 block w-full"
                                              required/>
                                <x-input-error class="mt-2" :messages="$errors->get('zip')"/>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="country" value="Country"/>
                            <x-select-input wire:model="country" id="country" name="country" class="mt-1 block w-full"
                                            :options="CountryEnum::options()"
                                            required/>
                            <x-input-error class="mt-2" :messages="$errors->get('country')"/>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>
                                <x-action-message class="mr-3" on="model-updated">
                                    {{ __('Saved.') }}
                                </x-action-message>
                            </div>
                            <x-secondary-button wire:click="closeModal">Close</x-secondary-button>
                        </div>
                    </form>
                </section>
            </div>
        </x-modal>
    @endif
</div>
