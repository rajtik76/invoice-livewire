@extends('layouts.pdf')

@section('style')
    <style>
        table {
            width: 100%;
            margin: auto;
        }

        .text-center {
            text-align: center;
        }

        .highlight {
            background-color: lightgray;
        }

        .highlight-sum {
            background-color: rgba(200, 200, 200, 0.3);
        }

        .highlight-sum td {
            padding: 0.25rem 0.5rem;
        }

        .highlight td {
            padding: 0.5rem;
        }

        .highlight th {
            padding: 0.5rem;
        }

        td {
            font-size: smaller;
            padding: 0 0.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="text-center">
        <h1>{{ __('base.time_log_report') }} {{ \Carbon\Carbon::create($report->year, $report->month)->monthName }} {{ $report->year }}</h1>
        <h2>{{ __('base.contract') }}: {{ $report->contract->name }}</h2>
        <h3>{{ __('base.total') }}: {{ collect($report->content)->sum(fn($items) => collect($items)->sum('hours')) }} {{ __('base.hours') }}</h3>
    </div>

    <div>
        <table>
            <tr class="highlight">
                <th>{{ __('base.date') }}</th>
                <th>{{ __('base.task') }}</th>
                <th>{{ __('base.hours') }}</th>
            </tr>
            <tbody>
            @foreach($report->content as $date => $tasks)
                @foreach($tasks as $task)
                    <tr>
                        <td>{{ $task['date'] }}</td>
                        <td>
                            @if($task['url'])
                                <a href="{{ $task['url'] }}">{{ $task['name'] }}</a>
                            @else
                                {{ $task['name'] }}
                            @endif
                        </td>
                        <td>{{ $task['hours'] }}</td>
                    </tr>
                @endforeach
                <x-report-day-summary :date="$date" :sum="collect($tasks)->sum('hours')"/>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
