<x-base-form>
    <x-slot name="head">
        {{ __('base.supplier_head') }}
    </x-slot>

    <x-slot name="paragraph">
        {{ __('base.supplier_paragraph') }}
    </x-slot>

    <div>
        <x-input-label for="address_id" :value="__('base.address')"/>
        <x-select-input wire:model="address_id" id="address_id" name="address_id" class="mt-1 block w-full"
                        :options="\App\Models\Address::getOptions()"
                        required autofocus/>
        <x-input-error class="mt-2" :messages="$errors->get('address_id')"/>
    </div>

    <div>
        <x-input-label for="bank_account_id" :value="__('base.bank_account')"/>
        <x-select-input wire:model="bank_account_id" id="bank_account_id" name="bank_account_id" class="mt-1 block w-full"
                        :options="\App\Models\BankAccount::getOptions()"
                        required/>
        <x-input-error class="mt-2" :messages="$errors->get('bank_account_id')"/>
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

    <div class="grid grid-cols-2 gap-4 w-full">
        <div>
            <x-input-label for="email" :value="__('base.email')"/>
            <x-text-input wire:model="email" id="email" name="email" type="text" class="mt-1 block w-full"
                          required/>
            <x-input-error class="mt-2" :messages="$errors->get('email')"/>
        </div>

        <div>
            <x-input-label for="phone" :value="__('base.phone')"/>
            <x-text-input wire:model="phone" id="phone" name="phone" type="text"
                          class="mt-1 block w-full"/>
            <x-input-error class="mt-2" :messages="$errors->get('phone')"/>
        </div>
    </div>
</x-base-form>
