<div>
    @if($isModalOpen)
        <x-modal name="submit" :show="true" :auto-close="false">
            <div class="p-4">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Supplier information
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Your information as a supplier.
                        </p>
                    </header>

                    <form wire:submit.prevent="submit" class="mt-6 space-y-6">
                        <div>
                            <x-input-label for="address_id" value="Address"/>
                            <x-select-input wire:model="address_id" id="address_id" name="address_id" class="mt-1 block w-full"
                                            :options="\App\Models\Address::getOptions()"
                                            required autofocus/>
                            <x-input-error class="mt-2" :messages="$errors->get('address_id')"/>
                        </div>

                        <div>
                            <x-input-label for="bank_account_id" value="Bank account"/>
                            <x-select-input wire:model="bank_account_id" id="bank_account_id" name="bank_account_id" class="mt-1 block w-full"
                                            :options="\App\Models\BankAccount::getOptions()"
                                            required/>
                            <x-input-error class="mt-2" :messages="$errors->get('bank_account_id')"/>
                        </div>

                        <div>
                            <x-input-label for="name" value="Name"/>
                            <x-text-input wire:model="name" id="name" name="name" type="text"
                                          class="mt-1 block w-full"
                                          required/>
                            <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                        </div>

                        <div class="grid grid-cols-2 gap-4 w-full">
                            <div>
                                <x-input-label for="vat_number" value="VAT #"/>
                                <x-text-input wire:model="vat_number" id="vat_number" name="vat_number" type="text" class="mt-1 block w-full"
                                              required/>
                                <x-input-error class="mt-2" :messages="$errors->get('vat_number')"/>
                            </div>

                            <div>
                                <x-input-label for="registration_number" value="Registration number"/>
                                <x-text-input wire:model="registration_number" id="registration_number" name="registration_number" type="text"
                                              class="mt-1 block w-full"/>
                                <x-input-error class="mt-2" :messages="$errors->get('registration_number')"/>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 w-full">
                            <div>
                                <x-input-label for="email" value="Email"/>
                                <x-text-input wire:model="email" id="email" name="email" type="text" class="mt-1 block w-full"
                                              required/>
                                <x-input-error class="mt-2" :messages="$errors->get('email')"/>
                            </div>

                            <div>
                                <x-input-label for="phone" value="Phone"/>
                                <x-text-input wire:model="phone" id="phone" name="phone" type="text"
                                              class="mt-1 block w-full"/>
                                <x-input-error class="mt-2" :messages="$errors->get('phone')"/>
                            </div>
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
