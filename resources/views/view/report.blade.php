<x-report-layout>
    <x-slot:title>
        {{ __('base.report_list') }} {{ $report->year }}_{{ $report->month }}
    </x-slot:title>

    <div class="p-6">
        <div class="border-8 border-gray-400 rounded-lg p-8">
            <!-- Title -->
            <div class="flex justify-between uppercase text-3xl font-bold">
                <p>{{ __('base.report_list') }} {{ $report->year }}/{{ $report->month }}</p>
                <p>{{ collect($report->content)->sum(fn($items) => collect($items)->sum('hours')) }} {{ __('base.hours') }} {{ __('base.total') }}</p>
            </div>

            <!-- Customer -->
            <div class="text-xl mt-2">
                {{ __('base.customer') }}: {{ $report->contract->customer->name }}
            </div>
        </div>

        <!-- Time log -->
        <div class="mt-8">
            <table class="w-full">
                <tr class="uppercase text-xl font-bold bg-gray-400 h-16 text-left">
                    <th class="pl-2">{{ __('base.date') }}</th>
                    <th>{{  __('base.task') }} / {{ __('base.note') }}</th>
                    <th>{{ __('base.hours') }}</th>
                </tr>

                @foreach($report->content as $date => $items)
                    @foreach($items as /** @var array{name: string, url: string|null, date: string, hours: float, comment: string|null} $task */$task)
                        <!-- Rows -->
                        <tr class="px-8 text-gray-500 odd:bg-slate-100">
                            <td class="pl-2 py-0.5">{{ $task['date'] }}</td>
                            <td>
                                <span class="flex flex-col">
                                @if($task['url'])
                                        <a href="{{ $task['url'] }}" class="underline text-blue-500">
                                        {{ $task['name'] }}
                                    </a>
                                    @else
                                        <div>{{ $task['name'] }}</div>
                                    @endif

                                    @if($task['comment'])
                                        <span class="italic text-xs">"{{ $task['comment'] }}"</span>
                                    @endif
                                </span>
                            </td>
                            <td>{{ $task['hours'] }}</td>
                        </tr>
                    @endforeach

                    <!-- Day total -->
                    <tr class="uppercase bg-sky-100 rounded-lg border border-slate-200 font-bold daily-total">
                        <td></td>
                        <td>{{ __('base.daily_total') }}</td>
                        <td>{{ number_format(collect($items)->sum('hours'), 1) }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</x-report-layout>
