<x-base-form>
    <x-slot name="head">
        {{ __('base.customer_head') }}
    </x-slot>

    <x-slot name="paragraph">
        {{ __('base.customer_paragraph') }}
    </x-slot>

    <div>
        <x-input-label for="address_id" :value="__('base.address')"/>
        <x-select-input wire:model="address_id" id="address_id" name="address_id" class="mt-1 block w-full"
                        :options="\App\Models\Address::getOptions()"
                        required autofocus/>
        <x-input-error class="mt-2" :messages="$errors->get('address_id')"/>
    </div>

    <div>
        <x-input-label for="name" :value="__('base.name')"/>
        <x-text-input wire:model="name" id="name" name="name" type="text"
                      class="mt-1 block w-full"
                      required/>
        <x-input-error class="mt-2" :messages="$errors->get('name')"/>
    </div>

    <div class="grid grid-cols-2 gap-4 w-full">
        <div>
            <x-input-label for="vat_number" :value="__('base.vat')"/>
            <x-text-input wire:model="vat_number" id="vat_number" name="vat_number" type="text" class="mt-1 block w-full"
                          required/>
            <x-input-error class="mt-2" :messages="$errors->get('vat_number')"/>
        </div>

        <div>
            <x-input-label for="registration_number" :value="__('base.registration')"/>
            <x-text-input wire:model="registration_number" id="registration_number" name="registration_number" type="text"
                          class="mt-1 block w-full"/>
            <x-input-error class="mt-2" :messages="$errors->get('registration_number')"/>
        </div>
    </div>
</x-base-form>
