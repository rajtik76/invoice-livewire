<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Contract list
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-end">
                        <x-orange-button
                            x-data
                            @click="$dispatch('open-create-form-modal')"
                            type="button"
                            class="mb-6"
                        >New Contract
                        </x-orange-button>
                    </div>
                    <livewire:table.contract-table :filters="['active' => 1]"/>
                    <livewire:form.contract-form/>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
