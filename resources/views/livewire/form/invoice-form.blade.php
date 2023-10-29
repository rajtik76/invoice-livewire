<x-base-form>
    <x-slot name="head">
        {{ __('base.invoice_head') }}
    </x-slot>

    <x-slot name="paragraph">
        {{ __('base.invoice_paragraph') }}
    </x-slot>

    <div>
        <x-input-label for="contract_id" :value="__('base.contract')"/>
        <x-select-input wire:model="contract_id" id="contract_id" name="contract_id"
                        class="mt-1 block w-full"
                        :options="App\Models\Contract::getOptions()"
                        required autofocus/>
        <x-input-error class="mt-2" :messages="$errors->get('contract_id')"/>
    </div>

    <div>
        <x-input-label for="number" :value="__('base.invoice')"/>
        <x-text-input wire:model="number" id="number" name="number" type="text"
                      class="mt-1 block w-full"
                      required/>
        <x-input-error class="mt-2" :messages="$errors->get('number')"/>
    </div>

    <div class="flex gap-1">
        <div>
            <x-input-label for="year" :value="__('base.year')"/>
            <x-text-input wire:model="year" id="year" name="year" type="number" step="1" min="2000"
                          max="{{ now()->year }}"
                          class="mt-1 block w-full"
                          required/>
            <x-input-error class="mt-2" :messages="$errors->get('year')"/>
        </div>

        <div>
            <x-input-label for="month" :value="__('base.month')"/>
            <x-text-input wire:model="month" id="month" name="month" type="number" step="1"
                          min="1" max="12"
                          class="mt-1 block w-full"
                          required/>
            <x-input-error class="mt-2" :messages="$errors->get('month')"/>
        </div>
    </div>

    <div class="flex gap-1">
        <div>
            <x-input-label for="issue_date" :value="__('base.issue_date')"/>
            <x-text-input wire:model="issue_date" id="issue_date" name="issue_date" type="date"
                          class="mt-1 block w-full"
                          required/>
            <x-input-error class="mt-2" :messages="$errors->get('issue_date')"/>
        </div>

        <div>
            <x-input-label for="due_date" :value="__('base.due_date')"/>
            <x-text-input wire:model="due_date" id="due_date" name="due_date" type="date"
                          class="mt-1 block w-full"
                          required/>
            <x-input-error class="mt-2" :messages="$errors->get('due_date')"/>
        </div>
    </div>
</x-base-form>
