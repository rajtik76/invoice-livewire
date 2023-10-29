<x-table-page>
    <x-slot name="title">
        {{ __('base.supplier_list') }}
    </x-slot>

    <x-slot name="createButton">
        {{ __('base.new_supplier') }}
    </x-slot>

    <livewire:table.supplier-table/>
    <livewire:form.supplier-form/>
</x-table-page>
