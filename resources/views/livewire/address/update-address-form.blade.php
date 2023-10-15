<?php

use App\Enums\CountryEnum;
use App\Models\Address;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use function Livewire\Volt\{mount, rules, state};

state(['address', 'street', 'city', 'zip', 'country']);

$addressModel = fn(): Address => Address::findOrFail($this->address);

mount(function () {
    /** @var Address $address */
    $address = $this->addressModel();

    if (auth()->user()->cannot('update', $address)) {
        abort(403);
    }

    $this->street = $address->street;
    $this->city = $address->city;
    $this->zip = $address->zip;
    $this->country = $address->country;
    $this->addressId = $address->id;
});

rules([
    'street' => ['required', 'string', 'max:255'],
    'city' => ['required', 'string', 'max:255'],
    'zip' => ['required', 'string', 'max:255'],
    'country' => ['required', new Enum(CountryEnum::class)],
]);

$updateAddressInformation = function () {
    $validated = $this->validate();
    /** @var Address $address */
    $address = $this->addressModel();

    if (auth()->user()->cannot('update', $address)) {
        abort(403);
    }

    $address->fill($validated);
    $address->save();

    $this->closeModal();
};

$closeModal = fn() => $this->dispatch('address-update-form-modal-close');

?>

<div>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Address information
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your customer or supplier address.
            </p>
        </header>

        <form wire:submit="updateAddressInformation" class="mt-6 space-y-6">
            <div>
                <x-input-label for="street" value="Street"/>
                <x-text-input wire:model="street" id="street" name="street" type="text" class="mt-1 block w-full"
                              required autofocus/>
                <x-input-error class="mt-2" :messages="$errors->get('street')"/>
            </div>

            <div class="grid grid-cols-2 gap-4 w-full">
                <div>
                    <x-input-label for="city" value="City"/>
                    <x-text-input wire:model="city" id="city" name="city" type="text" class="mt-1 block w-full"
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
                <x-primary-button>{{ __('Save') }}</x-primary-button>
                <x-secondary-button wire:click="closeModal">Close</x-secondary-button>
            </div>
        </form>
    </section>
</div>
