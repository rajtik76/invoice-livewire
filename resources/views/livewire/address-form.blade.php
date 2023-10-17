<div>
    @if($isModalOpen)
        <x-modal name="address-update-form" :show="true" :auto-close="false">
            <div class="p-4">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Address information
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Your customer or supplier address.
                        </p>
                    </header>

                    <form wire:submit.prevent="submit" class="mt-6 space-y-6">
                        <div>
                            <x-input-label for="street" value="Street"/>
                            <x-text-input wire:model="street" id="street" name="street" type="text"
                                          class="mt-1 block w-full"
                                          required autofocus/>
                            <x-input-error class="mt-2" :messages="$errors->get('street')"/>
                        </div>

                        <div class="grid grid-cols-2 gap-4 w-full">
                            <div>
                                <x-input-label for="city" value="City"/>
                                <x-text-input wire:model="city" id="city" name="city" type="text"
                                              class="mt-1 block w-full"
                                              required/>
                                <x-input-error class="mt-2" :messages="$errors->get('city')"/>
                            </div>

                            <div>
                                <x-input-label for="zip" value="ZIP"/>
                                <x-text-input wire:model="zip" id="zip" name="zip" type="text" class="mt-1 block w-full"
                                              required/>
                                <x-input-error class="mt-2" :messages="$errors->get('zip')"/>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="country" value="Country"/>
                            <x-select-input wire:model="country" id="country" name="country" class="mt-1 block w-full"
                                            :options="\App\Enums\CountryEnum::options()"
                                            required/>
                            <x-input-error class="mt-2" :messages="$errors->get('country')"/>
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
