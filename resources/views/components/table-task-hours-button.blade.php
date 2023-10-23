<x-orange-button class="!p-1 !text-sm">
    {{ $model->taskHour()->sum('hours') }}
</x-orange-button>
