<div>
    @if($this->isModalOpen)
        <x-modal name="submit" :show="true" :auto-close="false">
            <div class="p-4">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ $head }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ $paragraph }}
                        </p>
                    </header>

                    <form wire:submit.prevent="submit" class="mt-6 space-y-6">

                        {{ $slot }}

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('base.save') }}</x-primary-button>
                                <x-action-message class="mr-3" on="model-updated">
                                    {{ __('base.saved') }}
                                </x-action-message>
                            </div>
                            <x-secondary-button wire:click="closeModal">{{ __('base.close') }}</x-secondary-button>
                        </div>
                    </form>
                </section>
            </div>
        </x-modal>
    @endif
</div>
