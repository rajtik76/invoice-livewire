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
                        <div class="grid grid-cols-2 gap-4 w-full">
                            <div>
                                <x-input-label for="customer_id" value="Customer"/>
                                <x-select-input wire:model="customer_id" id="customer_id" name="customer_id"
                                                class="mt-1 block w-full"
                                                :options="\App\Models\Customer::getOptions()"
                                                required autofocus/>
                                <x-input-error class="mt-2" :messages="$errors->get('customer_id')"/>
                            </div>

                            <div>
                                <x-input-label for="supplier_id" value="Supplier"/>
                                <x-select-input wire:model="supplier_id" id="supplier_id" name="supplier_id"
                                                class="mt-1 block w-full"
                                                :options="\App\Models\Supplier::getOptions()"
                                                required/>
                                <x-input-error class="mt-2" :messages="$errors->get('supplier_id')"/>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="name" value="Name"/>
                            <x-text-input wire:model="name" id="name" name="name" type="text"
                                          class="mt-1 block w-full"
                                          required/>
                            <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                        </div>

                        <div>
                            <x-input-label for="signed_at" value="Signed"/>
                            <x-text-input wire:model="signed_at" id="signed_at" name="signed_at" type="date"
                                          class="mt-1 block w-full"
                                          required/>
                            <x-input-error class="mt-2" :messages="$errors->get('signed_at')"/>
                        </div>

                        <div class="grid grid-cols-2 gap-4 w-full">
                            <div>
                                <x-input-label for="price_per_hour" value="Price/Hour"/>
                                <x-text-input wire:model="price_per_hour" id="price_per_hour" name="price_per_hour"
                                              type="number" step="0.01"
                                              class="mt-1 block w-full"
                                              required/>
                                <x-input-error class="mt-2" :messages="$errors->get('price_per_hour')"/>
                            </div>

                            <div>
                                <x-input-label for="currency" value="Currency"/>
                                <x-select-input wire:model="currency" id="currency" name="currency"
                                                class="mt-1 block w-full"
                                                :options="\App\Enums\CurrencyEnum::getOptions()"
                                                required/>
                                <x-input-error class="mt-2" :messages="$errors->get('currency')"/>
                            </div>
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
