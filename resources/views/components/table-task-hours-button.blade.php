<x-orange-button class="!p-1 !text-sm" wire:click="redirectToTaskHour({{ $model->id }})">
    {{ $model->taskHour()->sum('hours') }}
</x-orange-button>
