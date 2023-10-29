<x-table-page>
    <x-slot name="title">
        {{ __('base.customer_list') }}
    </x-slot>

    <x-slot name="createButton">
        {{ __('base.new_customer') }}
    </x-slot>

    <livewire:table.customer-table/>
    <livewire:form.customer-form/>
</x-table-page>
