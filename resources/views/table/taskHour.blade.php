<x-table-page>
    <x-slot name="title">
        {{ __('base.task_hour_list') }}
    </x-slot>

    <x-slot name="createButton">
        {{ __('base.new_task_hour') }}
    </x-slot>

    <livewire:table.task-hour-table
        sort-column="date"
        :sort-direction="\RamonRietdijk\LivewireTables\Enums\Direction::Descending->value"
        :filters="['task_id' => request()->task]"
    />
    <livewire:form.taskHour-form :task="request()->task"/>
</x-table-page>
