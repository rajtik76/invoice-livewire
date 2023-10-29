<x-table-page>
    <x-slot name="title">
        {{ __('base.address_list') }}
    </x-slot>

    <x-slot name="createButton">
        {{ __('base.new_address') }}
    </x-slot>

    <livewire:table.address-table/>
    <livewire:form.address-form/>
</x-table-page>
