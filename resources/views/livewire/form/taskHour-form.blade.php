@php use App\Models\Contract; @endphp
@php use App\Enums\CurrencyEnum;use App\Models\Task; @endphp
<div>
    @if($isModalOpen)
        <x-modal name="submit" :show="true" :auto-close="false">
            <div class="p-4">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Task time log information
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Log your time spent on the task.
                        </p>
                    </header>

                    <form wire:submit.prevent="submit" class="mt-6 space-y-6">
                        <div>
                            <x-input-label for="task_id" value="Task"/>
                            <x-select-input wire:model="task_id" id="task_id" name="task_id"
                                            class="mt-1 block w-full"
                                            :disabled="$task"
                                            :options="Task::getOptions()"
                                            required autofocus/>
                            <x-input-error class="mt-2" :messages="$errors->get('task_id')"/>
                        </div>

                        <div>
                            <x-input-label for="date" value="Date"/>
                            <x-text-input wire:model="date" id="date" date="date" type="date"
                                          class="mt-1 block w-full"
                                          required/>
                            <x-input-error class="mt-2" :messages="$errors->get('date')"/>
                        </div>

                        <div>
                            <x-input-label for="hours" value="Hours"/>
                            <x-text-input wire:model="hours" id="hours" name="hours" type="number" step="0.1"
                                          class="mt-1 block w-full"/>
                            <x-input-error class="mt-2" :messages="$errors->get('hours')"/>
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
