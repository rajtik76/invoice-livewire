<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-3 gap-5">
                        <x-info-widget>
                            <x-slot name="header">
                                Active tasks
                            </x-slot>
                            <x-slot name="content">
                                {{ $tasks }}
                            </x-slot>
                        </x-info-widget>

                        <x-info-widget>
                            <x-slot name="header">
                                Hours this month
                            </x-slot>
                            <x-slot name="content">
                                {{ $hours }}
                            </x-slot>
                        </x-info-widget>

                        <x-info-widget>
                            <x-slot name="header">
                                This month invoice amount
                            </x-slot>
                            <x-slot name="content">
                                @foreach($amount as $currency => $amount)
                                    <div>
                                        {{ number_format($amount, 2) }} {{ \App\Enums\CurrencyEnum::from($currency)->getCurrencySymbol() }}
                                    </div>
                                @endforeach
                            </x-slot>
                        </x-info-widget>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
