<x-table-page>
    <x-slot name="title">
        {{ __('base.task_list') }}
    </x-slot>

    <x-slot name="createButton">
        {{ __('base.new_task') }}
    </x-slot>

    <livewire:table.task-table :filters="['active' => 1]"
                               sort-column="name"
                               :sort-direction="\RamonRietdijk\LivewireTables\Enums\Direction::Descending->value"/>
    <livewire:form.task-form/>
</x-table-page>
