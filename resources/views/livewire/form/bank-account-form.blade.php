<div>
    @if($isModalOpen)
        <x-modal name="submit" :show="true" :auto-close="false">
            <div class="p-4">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Bank account information
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Your customer or supplier bank account information.
                        </p>
                    </header>

                    <form wire:submit.prevent="submit" class="mt-6 space-y-6">
                        <div>
                            <x-input-label for="bank_name" value="Bank name"/>
                            <x-text-input wire:model="bank_name" id="bank_name" name="bank_name" type="text"
                                          class="mt-1 block w-full"
                                          required autofocus/>
                            <x-input-error class="mt-2" :messages="$errors->get('bank_name')"/>
                        </div>

                        <div class="grid grid-cols-2 gap-4 w-full">
                            <div>
                                <x-input-label for="account_number" value="Account number"/>
                                <x-text-input wire:model="account_number" id="account_number" name="account_number" type="text"
                                              class="mt-1 block w-full"
                                              required/>
                                <x-input-error class="mt-2" :messages="$errors->get('account_number')"/>
                            </div>

                            <div>
                                <x-input-label for="bank_number" value="Bank number"/>
                                <x-text-input wire:model="bank_number" id="bank_number" name="bank_number" type="text" class="mt-1 block w-full"
                                              required/>
                                <x-input-error class="mt-2" :messages="$errors->get('bank_number')"/>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="iban" value="IBAN"/>
                            <x-text-input wire:model="iban" id="iban" name="iban" type="text" class="mt-1 block w-full"
                                          required/>
                            <x-input-error class="mt-2" :messages="$errors->get('iban')"/>
                        </div>

                        <div>
                            <x-input-label for="swift" value="SWIFT"/>
                            <x-text-input wire:model="swift" id="swift" name="swift" type="text" class="mt-1 block w-full"
                                          required/>
                            <x-input-error class="mt-2" :messages="$errors->get('swift')"/>
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
