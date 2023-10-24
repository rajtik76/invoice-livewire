<x-table-page>
    <x-slot name="title">
        Task hour list
    </x-slot>

    <x-slot name="createButton">
        Log New Task Hours
    </x-slot>

    <livewire:table.task-hour-table
        sort-column="date"
        :sort-direction="\RamonRietdijk\LivewireTables\Enums\Direction::Descending->value"
        :filters="['task_id' => request()->task]"
    />
    <livewire:form.taskHour-form :task="request()->task"/>
</x-table-page>
