<x-base-form>
    <x-slot name="head">
        {{ __('base.contract_head') }}
    </x-slot>

    <x-slot name="paragraph">
        {{ __('base.contract_paragraph') }}
    </x-slot>

    <div class="grid grid-cols-2 gap-4 w-full">
        <div>
            <x-input-label for="customer_id" :value="__('base.customer')"/>
            <x-select-input wire:model="customer_id" id="customer_id" name="customer_id"
                            class="mt-1 block w-full"
                            :options="\App\Models\Customer::getOptions()"
                            required autofocus/>
            <x-input-error class="mt-2" :messages="$errors->get('customer_id')"/>
        </div>

        <div>
            <x-input-label for="supplier_id" :value="__('base.supplier')"/>
            <x-select-input wire:model="supplier_id" id="supplier_id" name="supplier_id"
                            class="mt-1 block w-full"
                            :options="\App\Models\Supplier::getOptions()"
                            required/>
            <x-input-error class="mt-2" :messages="$errors->get('supplier_id')"/>
        </div>
    </div>

    <div>
        <x-input-label for="name" :value="__('base.name')"/>
        <x-text-input wire:model="name" id="name" name="name" type="text"
                      class="mt-1 block w-full"
                      required/>
        <x-input-error class="mt-2" :messages="$errors->get('name')"/>
    </div>

    <div>
        <x-input-label for="signed_at" :value="__('base.signed_at')"/>
        <x-text-input wire:model="signed_at" id="signed_at" name="signed_at" type="date"
                      class="mt-1 block w-full"
                      required/>
        <x-input-error class="mt-2" :messages="$errors->get('signed_at')"/>
    </div>

    <div class="grid grid-cols-2 gap-4 w-full">
        <div>
            <x-input-label for="price_per_hour" :value="__('base.price')"/>
            <x-text-input wire:model="price_per_hour" id="price_per_hour" name="price_per_hour"
                          type="number" step="0.01"
                          class="mt-1 block w-full"
                          required/>
            <x-input-error class="mt-2" :messages="$errors->get('price_per_hour')"/>
        </div>

        <div>
            <x-input-label for="currency" :value="__('base.currency')"/>
            <x-select-input wire:model="currency" id="currency" name="currency"
                            class="mt-1 block w-full"
                            :options="\App\Enums\CurrencyEnum::getOptions()"
                            required/>
            <x-input-error class="mt-2" :messages="$errors->get('currency')"/>
        </div>
    </div>

    <div>
        <x-input-label for="active" :value="__('base.active')"/>
        <x-input-check wire:model="active" id="active" name="active"
                       class="mt-1 block"/>
        <x-input-error class="mt-2" :messages="$errors->get('active')"/>
    </div>
</x-base-form>
