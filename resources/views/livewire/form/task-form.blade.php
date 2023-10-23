@php use App\Models\Contract; @endphp
@php use App\Enums\CurrencyEnum; @endphp
<div>
    @if($isModalOpen)
        <x-modal name="submit" :show="true" :auto-close="false">
            <div class="p-4">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Contract information
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Your contract information.
                        </p>
                    </header>

                    <form wire:submit.prevent="submit" class="mt-6 space-y-6">
                        <div>
                            <x-input-label for="contract_id" value="Contract"/>
                            <x-select-input wire:model="contract_id" id="contract_id" name="contract_id"
                                            class="mt-1 block w-full"
                                            :options="Contract::getOptions()"
                                            required autofocus/>
                            <x-input-error class="mt-2" :messages="$errors->get('contract_id')"/>
                        </div>

                        <div>
                            <x-input-label for="name" value="Name"/>
                            <x-text-input wire:model="name" id="name" name="name" type="text"
                                          class="mt-1 block w-full"
                                          required/>
                            <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                        </div>

                        <div>
                            <x-input-label for="url" value="URL"/>
                            <x-text-input wire:model="url" id="url" name="url" type="text"
                                          class="mt-1 block w-full"/>
                            <x-input-error class="mt-2" :messages="$errors->get('url')"/>
                        </div>

                        <div>
                            <x-input-label for="note" value="Note"/>
                            <x-text-input wire:model="note" id="note" name="note"
                                          type="text"
                                          class="mt-1 block w-full"/>
                            <x-input-error class="mt-2" :messages="$errors->get('note')"/>
                        </div>

                        <div>
                            <x-input-label for="active" value="Active"/>
                            <x-input-check wire:model="active" id="active" name="active"
                                           class="mt-1 block"/>
                            <x-input-error class="mt-2" :messages="$errors->get('active')"/>
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
