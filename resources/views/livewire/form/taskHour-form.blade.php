<x-base-form>
    <x-slot name="head">
        {{ __('base.task_hour_head') }}
    </x-slot>

    <x-slot name="paragraph">
        {{ __('base.task_hour_paragraph') }}
    </x-slot>

    <div>
        <x-input-label for="task_id" value="Task"/>
        <x-select-input wire:model="task_id" id="task_id" name="task_id"
                        class="mt-1 block w-full"
                        :disabled="$task"
                        :options="\App\Models\Task::getOptions()"
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
</x-base-form>
