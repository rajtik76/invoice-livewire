<x-base-form>
    <x-slot name="head">
        {{ __('base.bank_account_head') }}
    </x-slot>

    <x-slot name="paragraph">
        {{ __('base.bank_account_paragraph') }}
    </x-slot>

    <div>
        <x-input-label for="bank_name" :value="__('base.bank_name')"/>
        <x-text-input wire:model="bank_name" id="bank_name" name="bank_name" type="text"
                      class="mt-1 block w-full"
                      required autofocus/>
        <x-input-error class="mt-2" :messages="$errors->get('bank_name')"/>
    </div>

    <div class="grid grid-cols-2 gap-4 w-full">
        <div>
            <x-input-label for="account_number" :value="__('base.account')"/>
            <x-text-input wire:model="account_number" id="account_number" name="account_number" type="text"
                          class="mt-1 block w-full"
                          required/>
            <x-input-error class="mt-2" :messages="$errors->get('account_number')"/>
        </div>

        <div>
            <x-input-label for="bank_code" :value="__('base.bank_code')"/>
            <x-text-input wire:model="bank_code" id="bank_code" name="bank_code" type="text" class="mt-1 block w-full"
                          required/>
            <x-input-error class="mt-2" :messages="$errors->get('bank_code')"/>
        </div>
    </div>

    <div>
        <x-input-label for="iban" :value="__('base.iban')"/>
        <x-text-input wire:model="iban" id="iban" name="iban" type="text" class="mt-1 block w-full"
                      required/>
        <x-input-error class="mt-2" :messages="$errors->get('iban')"/>
    </div>

    <div>
        <x-input-label for="swift" :value="__('base.swift')"/>
        <x-text-input wire:model="swift" id="swift" name="swift" type="text" class="mt-1 block w-full"
                      required/>
        <x-input-error class="mt-2" :messages="$errors->get('swift')"/>
    </div>
</x-base-form>
