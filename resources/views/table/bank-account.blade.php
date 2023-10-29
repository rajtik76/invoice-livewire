<x-table-page>
    <x-slot name="title">
        {{ __('base.bank_account_list') }}
    </x-slot>

    <x-slot name="createButton">
        {{ __('base.new_bank_account') }}
    </x-slot>

    <livewire:table.bank-account-table/>
    <livewire:form.bank-account-form/>
</x-table-page>
