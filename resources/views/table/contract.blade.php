<x-table-page>
    <x-slot name="title">
        {{ __('base.contract_list') }}
    </x-slot>

    <x-slot name="createButton">
        {{ __('base.new_contract') }}
    </x-slot>

    <livewire:table.contract-table :filters="['active' => 1]"/>
    <livewire:form.contract-form/>
</x-table-page>
