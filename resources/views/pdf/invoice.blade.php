@extends('layouts.pdf')

@section('style')
    <style>
        .m-auto {
            margin: auto;
        }

        .w-full {
            width: 100%;
        }

        .upper {
            text-transform: uppercase;
        }

        .text-xs {
            font-size: 0.65rem;
        }

        .contract-entities td {
            font-size: 0.85rem;
        }

        .mt-2 {
            padding-top: 2em;
        }

        .mt-4 {
            padding-top: 4em;
        }

        th {
            text-align: left;
        }

        .border-1 {
            border: 1px solid lightgray;
        }

        .rounded {
            border-radius: 0.25em;
        }

        .invoice-info td {
            font-size: 0.65rem;
        }

        .invoice-content td {
            font-size: 0.7rem;
        }

        .total td {
            font-size: 1rem;
            text-transform: uppercase;
        }

        .text-right {
            text-align: right;
        }

        .text-xl {
            font-size: 2rem;
        }
    </style>
@endsection

@section('content')
    <header>
        <h1 class="upper">{{ __('base.invoice') }} #{{ $invoice->number }}</h1>
    </header>

    <main>
        <div class="border-1 rounded">
            <table class="m-auto w-full contract-entities">
                <tr>
                    <th>{{ __('base.supplier') }}</th>
                    <th>{{ __('base.customer') }}</th>
                </tr>
                <tr>
                    <td>{{ $invoice->contract->supplier->name }}</td>
                    <td>{{ $invoice->contract->customer->name }}</td>
                </tr>
                <tr>
                    <td>{{ $invoice->contract->supplier->address->street }}</td>
                    <td>{{ $invoice->contract->customer->address->street }}</td>
                </tr>
                <tr>
                    <td>{{ $invoice->contract->supplier->address->zip }} {{ $invoice->contract->supplier->address->city }}</td>
                    <td>{{ $invoice->contract->customer->address->zip }} {{ $invoice->contract->customer->address->city }}</td>
                </tr>
                <tr>
                    <td>{{ $invoice->contract->supplier->address->country->countryName() }}</td>
                    <td>{{ $invoice->contract->customer->address->country->countryName() }}</td>
                </tr>
                <tr>
                    <td>
                        <em class="text-xs">{{ __('base.email') }}: {{ $invoice->contract->supplier->email }}</em>
                    </td>
                </tr>
                <tr>
                    <td>
                        <em class="text-xs">{{ __('base.phone') }}: {{ $invoice->contract->supplier->phone }}</em>
                    </td>
                </tr>
                <tr>
                    <td>{{ __('base.registration') }} No.: {{ $invoice->contract->supplier->registration_number }}</td>
                    <td>
                        @isset($invoice->contract->customer->registration_number)
                            {{ __('base.registration') }} No.: {{ $invoice->contract->customer->registration_number }}
                        @endisset
                    </td>
                </tr>
                <tr>
                    <td>{{ __('base.vat') }} No.: {{ $invoice->contract->supplier->vat_number }}</td>
                    <td>{{ __('base.vat') }} No.: {{ $invoice->contract->customer->vat_number }}</td>
                </tr>
            </table>
        </div>

        <div class="mt-2">
            <table class="m-auto w-full invoice-info">
                <tr>
                    <th>{{ __('base.payment_advice') }}</th>
                    <th>{{ __('base.dates') }}</th>
                </tr>
                <tr>
                    <td>{{ __('base.bank_name') }}: {{ $invoice->contract->supplier->bankAccount->bank_name }}</td>
                    <td>{{ __('base.issue_date') }}: {{ $invoice->issue_date->toDateString() }}</td>
                </tr>
                <tr>
                    <td>{{ __('base.bank_account') }}
                        : {{ $invoice->contract->supplier->bankAccount->account_number }}</td>
                    <td>{{ __('base.due_date') }}: {{ $invoice->due_date->toDateString() }}</td>
                </tr>
                <tr>
                    <td>{{ __('base.iban') }}: {{ $invoice->contract->supplier->bankAccount->iban }}</td>
                </tr>
                <tr>
                    <td>{{ __('base.swift') }}: {{ $invoice->contract->supplier->bankAccount->swift }}</td>
                </tr>
                <tr>
                    <td>{{ __('base.reference_id') }}: {{ $invoice->number }}</td>
                </tr>
            </table>
        </div>

        <div class="mt-2">
            <table class="w-full m-auto invoice-content">
                <thead>
                <tr>
                    <th>Task</th>
                    <th>Unit price</th>
                    <th>Hours</th>
                    <th>Amount</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoice->content as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ number_format($invoice->contract->price_per_hour, 2) }} {{ $invoice->contract->currency->getCurrencySymbol() }}</td>
                        <td>{{ number_format($item['hours'], 1) }}</td>
                        <td>{{ number_format($item['amount'], 2) }} {{ $invoice->contract->currency->getCurrencySymbol() }}</td>
                    </tr>
                @endforeach
                <tr class="total">
                    <td><strong>{{ __('base.total') }}</strong></td>
                    <td><strong></strong></td>
                    <td><strong>{{ number_format(collect($invoice->content)->sum('hours'), 1) }}</strong></td>
                    <td>
                        <strong>{{ number_format($invoice->total_amount, 2) }} {{ $invoice->contract->currency->getCurrencySymbol() }}</strong>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-right">
            <p class="text-xl upper">
                <strong>
                    {{ __('base.total') }}
                    : {{ number_format($invoice->total_amount) }} {{ $invoice->contract->currency->getCurrencySymbol() }}
                </strong>
            </p>
        </div>
    </main>
@endsection
