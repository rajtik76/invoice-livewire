@php use App\Enums\CurrencyEnum; @endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('base.dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid gap-5 grid-cols-1 sm:grid-cols-2 md:grid-cols-3">
                        <x-info-widget>
                            <x-slot name="header">
                                {{ __('base.active_task') }}
                            </x-slot>
                            <x-slot name="content">
                                {{ $tasks }}
                            </x-slot>
                        </x-info-widget>

                        <x-info-widget>
                            <x-slot name="header">
                                {{ __('base.current_month_hours', ['year' => now()->year, 'month' => now()->monthName]) }}
                            </x-slot>
                            <x-slot name="content">
                                {{ $hours }}
                            </x-slot>
                        </x-info-widget>

                        <x-info-widget>
                            <x-slot name="header">
                                {{ __('base.current_month_amount', ['year' => now()->year, 'month' => now()->monthName]) }}
                            </x-slot>
                            <x-slot name="content">
                                @if($amount->count())
                                    @foreach($amount as $currency => $amount)
                                        <div>
                                            {{ number_format($amount, 2) }} {{ CurrencyEnum::from($currency)->getCurrencySymbol() }}
                                        </div>
                                    @endforeach
                                @else
                                    <div>0.00 €</div>
                                @endif
                            </x-slot>
                        </x-info-widget>
                    </div>
                    @if(true)
                        <div class="mt-5">
                            <x-widget-overlay>
                                <div class="flex flex-col gap-3">
                                    <div class="font-bold text-2xl text-yellow-200 text-center">{{ __('base.top_three_tasks', ['count' => $topThreeTaskCount, 'year' => now()->year, 'month' => now()->monthName]) }}</div>
                                    <div>
                                        <table class="m-auto w-full">
                                            <tr class="text-left text-yellow-100">
                                                <th>{{ __('base.customer') }}</th>
                                                <th>{{ __('base.task') }}</th>
                                                <th>{{ __('base.hours') }}</th>
                                                <th>{{ __('base.amount') }}</th>
                                            </tr>
                                            @foreach($topThreeTask as $task)
                                                <tr>
                                                    <td>{{ $task->contract->customer->name }}</td>
                                                    <td>
                                                        @if($task->url)
                                                            <a href="{{ $task->url }}" class="underline" target="_blank">{{ $task->name }}</a>
                                                        @else
                                                            {{ $task->name }}
                                                        @endif
                                                    </td>
                                                    <td>{{ Number::format($task->task_hours_sum_hours, 1) }}</td>
                                                    <td>{{ Number::format($task->task_hours_sum_hours * $task->contract->price_per_hour, 2) }} {{ $task->contract->currency->getCurrencySymbol() }}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </x-widget-overlay>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
