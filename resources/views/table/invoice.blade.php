<x-table-page>
    <x-slot name="title">
        {{ __('base.invoice_list') }}
    </x-slot>

    <x-slot name="createButton">
        {{ __('base.new_invoice') }}
    </x-slot>

    <livewire:table.invoice-table sort-column="due_date" :sort-direction="\RamonRietdijk\LivewireTables\Enums\Direction::Descending->value" />
    <livewire:form.invoice-form/>
</x-table-page>
