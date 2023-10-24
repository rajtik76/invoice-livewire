<x-table-page>
    <x-slot name="title">
        Contract list
    </x-slot>

    <x-slot name="createButton">
        New Contract
    </x-slot>

    <livewire:table.contract-table :filters="['active' => 1]"/>
    <livewire:form.contract-form/>
</x-table-page>
