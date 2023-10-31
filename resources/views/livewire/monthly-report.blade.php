@php use App\Models\Contract; @endphp
<section>
    <form wire:submit="export" class="mt-6 space-y-6">
        <div>
            <x-input-label for="contract_id" :value="__('base.contract')"/>
            <x-select-input wire:model="contract_id" id="contract_id" name="contract_id" class="mt-1 block w-full"
                            :options="Contract::getOptions()"
                            required autofocus/>
            <x-input-error class="mt-2" :messages="$errors->get('contract_id')"/>
        </div>

        <div class="flex gap-4">
            <div>
                <x-input-label for="year" :value="__('base.year')"/>
                <x-text-input wire:model="year" id="year" name="year" type="number" step="1" min="1900"
                              max="{{ now()->year }}" class="mt-1 block w-full" required/>
                <x-input-error class="mt-2" :messages="$errors->get('year')"/>
            </div>

            <div>
                <x-input-label for="month" :value="__('base.month')"/>
                <x-text-input wire:model="month" id="month" name="month" type="number" step="1" min="1" max="12"
                              class="mt-1 block w-full"
                              required/>
                <x-input-error class="mt-2" :messages="$errors->get('month')"/>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Export') }}</x-primary-button>
        </div>
    </form>
</section>
