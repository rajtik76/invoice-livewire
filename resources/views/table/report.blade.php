<x-table-page>
    <x-slot name="title">
        {{ __('base.report_list') }}
    </x-slot>

    <x-slot name="createButton">
        {{ __('base.new_report') }}
    </x-slot>

    <livewire:table.report-table sort-column="year" sort-direction="desc"/>
    <livewire:form.report-form/>
</x-table-page>
