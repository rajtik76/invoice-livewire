@extends('layouts.pdf')

@section('style')
    <style>
        header, footer {
            position: fixed;
            width: 100%;
            z-index: 20;
        }

        header {
            top: 0;
            height: 5em;
        }

        footer {
            bottom: 0;
            height: 3em;
        }

        main {
            margin: 21em 0 4em 0em;
        }

        .supplier, .customer {
            position: fixed;
            width: 45%;
            height: 14em;
            overflow: hidden;
            border: 2px solid grey;
            padding: 0.5em;
        }

        .customer {
            margin-left: 22em;
        }
    </style>
@endsection

@section('content')
    <header>
        <h1 class="">Invoice #2023001</h1>
        <div class="supplier">
            <strong>Supplier</strong><br><br>
            {{ $invoice->contract->supplier->name }}<br>
            {{ $invoice->contract->supplier->address->street }}<br>
            {{ $invoice->contract->supplier->address->zip }} {{ $invoice->contract->supplier->address->city }}<br>
            {{ $invoice->contract->supplier->address->country->countryName() }}<br><br>

            <div style="font-size: 75%;">
                <em>
                    Email: {{ $invoice->contract->supplier->email }}<br>
                    Phone: {{ $invoice->contract->supplier->phone }}<br><br>
                </em>
                Registration No. {{ $invoice->contract->supplier->registration_number }}<br>
                VAT No. {{ $invoice->contract->supplier->vat_number }}
            </div>
        </div>
        <div class="customer">
            <strong>Customer</strong><br><br>
            {{ $invoice->contract->customer->name }}<br>
            {{ $invoice->contract->customer->address->street }}<br>
            {{ $invoice->contract->customer->address->zip }} {{ $invoice->contract->customer->address->city }}<br>
            {{ $invoice->contract->customer->address->country->countryName() }}<br><br>

            <div style="font-size: 75%;">
                Registration No. {{ $invoice->contract->customer->registration_number }}<br>
                VAT No. {{ $invoice->contract->customer->vat_number }}
            </div>
        </div>
    </header>

    <main>
        Content
    </main>

    <footer>
        Footer
    </footer>
@endsection
