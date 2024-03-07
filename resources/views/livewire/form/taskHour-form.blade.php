@php use App\Models\Task; @endphp
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
                        :options="Task::getOptions()"
                        required autofocus/>
        <x-input-error class="mt-2" :messages="$errors->get('task_id')"/>
    </div>

    <div class="flex gap-2">
        <div>
            <x-input-label for="date" value="Date"/>
            <x-text-input wire:model="date" id="date" date="date" type="date"
                          class="mt-1 block"
                          required/>
            <x-input-error class="mt-2" :messages="$errors->get('date')"/>
        </div>

        <div>
            <x-input-label for="hours" value="Hours"/>
            <x-text-input wire:model="hours" id="hours" name="hours" type="number" step="0.1"
                          class="mt-1 block w-32"/>
            <x-input-error class="mt-2" :messages="$errors->get('hours')"/>
        </div>
    </div>

    <div>
        <x-input-label for="comment" value="Comment"/>
        <x-text-input wire:model="comment" id="comment" name="comment" class="mt-1 block w-full"/>
        <x-input-error class="mt-2" :messages="$errors->get('comment')"/>
    </div>
</x-base-form>
