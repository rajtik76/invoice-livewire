<x-base-form>
    <x-slot name="head">
        {{ __('base.task_head') }}
    </x-slot>

    <x-slot name="paragraph">
        {{ __('base.task_paragraph') }}
    </x-slot>

    <div>
        <x-input-label for="contract_id" :value="__('base.contract')"/>
        <x-select-input wire:model="contract_id" id="contract_id" name="contract_id"
                        class="mt-1 block w-full"
                        :options="\App\Models\Contract::getOptions()"
                        required autofocus/>
        <x-input-error class="mt-2" :messages="$errors->get('contract_id')"/>
    </div>

    <div>
        <x-input-label for="name" :value="__('base.name')"/>
        <x-text-input wire:model="name" id="name" name="name" type="text"
                      class="mt-1 block w-full"
                      required/>
        <x-input-error class="mt-2" :messages="$errors->get('name')"/>
    </div>

    <div>
        <x-input-label for="url" :value="__('base.url')"/>
        <x-text-input wire:model="url" id="url" name="url" type="text"
                      class="mt-1 block w-full"/>
        <x-input-error class="mt-2" :messages="$errors->get('url')"/>
    </div>

    <div>
        <x-input-label for="note" :value="__('base.note')"/>
        <x-text-input wire:model="note" id="note" name="note"
                      type="text"
                      class="mt-1 block w-full"/>
        <x-input-error class="mt-2" :messages="$errors->get('note')"/>
    </div>

    <div>
        <x-input-label for="active" :value="__('base.active')"/>
        <x-input-check wire:model="active" id="active" name="active"
                       class="mt-1 block"/>
        <x-input-error class="mt-2" :messages="$errors->get('active')"/>
    </div>
</x-base-form>
