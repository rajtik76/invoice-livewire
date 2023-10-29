<x-base-form>
    <x-slot name="head">
        {{ __('base.address_head') }}
    </x-slot>

    <x-slot name="paragraph">
        {{ __('base.address_paragraph') }}
    </x-slot>

    <div>
        <x-input-label for="street" :value="__('base.street')"/>
        <x-text-input wire:model="street" id="street" name="street" type="text"
                      class="mt-1 block w-full"
                      required autofocus/>
        <x-input-error class="mt-2" :messages="$errors->get('street')"/>
    </div>

    <div class="grid grid-cols-2 gap-4 w-full">
        <div>
            <x-input-label for="city" :value="__('base.city')"/>
            <x-text-input wire:model="city" id="city" name="city" type="text"
                          class="mt-1 block w-full"
                          required/>
            <x-input-error class="mt-2" :messages="$errors->get('city')"/>
        </div>

        <div>
            <x-input-label for="zip" :value="__('base.zip')"/>
            <x-text-input wire:model="zip" id="zip" name="zip" type="text" class="mt-1 block w-full"
                          required/>
            <x-input-error class="mt-2" :messages="$errors->get('zip')"/>
        </div>
    </div>

    <div>
        <x-input-label for="country" :value="__('base.country')"/>
        <x-select-input wire:model="country" id="country" name="country" class="mt-1 block w-full"
                        :options="\App\Enums\CountryEnum::options()"
                        required/>
        <x-input-error class="mt-2" :messages="$errors->get('country')"/>
    </div>
</x-base-form>
