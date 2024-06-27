@php use App\Enums\CurrencyEnum; @endphp

<x-report-layout>
    <x-slot:title>
        {{ __('base.invoice') }} {{ $invoice->number }}
    </x-slot:title>

    <div class="grid grid-cols-2 gap-4 p-4">
        <!-- INVOICE -->
        <div class="col-span-2 uppercase font-bold text-6xl">{{ __('base.invoice') }} # {{ $invoice->number }}</div>

        <!-- SUPPLIER -->
        <x-invoice-grid-panel border="border-blue-200" class="bg-blue-200">
            <x-slot:title>{{ __('base.supplier') }}</x-slot:title>
            <div class="grid grid-flow-row-dense auto-rows-max">
                <x-invoice-grid-item class="text-xl font-bold">
                    {{ $invoice->contract->supplier->name }}
                </x-invoice-grid-item>
                <x-invoice-grid-item>{{ $invoice->contract->supplier->address->street }}</x-invoice-grid-item>
                <x-invoice-grid-item>{{ $invoice->contract->supplier->address->zip }} {{ $invoice->contract->supplier->address->city }}</x-invoice-grid-item>
                <x-invoice-grid-item>{{ $invoice->contract->supplier->address->country->countryName() }}</x-invoice-grid-item>
                @if($invoice->contract->supplier->phone && $invoice->contract->supplier->email)
                    <x-invoice-grid-item class="text-xs italic">
                        {{ __('base.phone') }}: {{ $invoice->contract->supplier->phone }},
                        {{ __('base.email') }}: {{ $invoice->contract->supplier->email }}
                    </x-invoice-grid-item>
                @else
                    <x-invoice-grid-item class="text-xs italic">&nbsp;</x-invoice-grid-item>
                @endif
                <x-invoice-grid-item>&nbsp;</x-invoice-grid-item>
                <x-invoice-grid-item
                    class="text-blue-500 text-sm">{{ __('base.supplier_is_registered_in_trade_register') }}</x-invoice-grid-item>
                <x-invoice-grid-item>&nbsp;</x-invoice-grid-item>
                @if($invoice->contract->supplier->registration_number)
                    <x-invoice-grid-item>
                        {{ __('base.registration') }}: {{ $invoice->contract->supplier->registration_number }}
                    </x-invoice-grid-item>
                @else
                    <x-invoice-grid-item>&nbsp;</x-invoice-grid-item>
                @endif
                @if($invoice->contract->currency !== CurrencyEnum::CZK)
                    <x-invoice-grid-item class="font-bold">
                        {{ __('base.vat') }}: {{ $invoice->contract->supplier->vat_number }}
                    </x-invoice-grid-item>
                @endif
            </div>
        </x-invoice-grid-panel>

        <!-- CUSTOMER -->
        <x-invoice-grid-panel border="border-green-200" class="bg-green-200">
            <x-slot:title>{{ __('base.customer') }}</x-slot:title>
            <div class="grid grid-flow-row-dense auto-rows-max">
                <x-invoice-grid-item class="text-xl font-bold">
                    {{ $invoice->contract->customer->name }}
                </x-invoice-grid-item>
                <x-invoice-grid-item>{{ $invoice->contract->customer->address->street }}</x-invoice-grid-item>
                <x-invoice-grid-item>{{ $invoice->contract->customer->address->zip }} {{ $invoice->contract->customer->address->city }}</x-invoice-grid-item>
                <x-invoice-grid-item>{{ $invoice->contract->customer->address->country->countryName() }}</x-invoice-grid-item>
                @if($invoice->contract->customer->phone && $invoice->contract->customer->email)
                    <x-invoice-grid-item class="text-xs italic">
                        {{ __('base.phone') }}: {{ $invoice->contract->customer->phone }},
                        {{ __('base.email') }}: {{ $invoice->contract->customer->email }}
                    </x-invoice-grid-item>
                @else
                    <x-invoice-grid-item class="text-xs italic">&nbsp;</x-invoice-grid-item>
                @endif
                <x-invoice-grid-item>&nbsp;</x-invoice-grid-item>
                <x-invoice-grid-item
                    class="text-blue-500 text-sm">{{ __('base.vat_reverse_charge_mode') }}
                </x-invoice-grid-item>
                @if($invoice->contract->customer->registration_number)
                    <x-invoice-grid-item>
                        {{ __('base.registration') }}: {{ $invoice->contract->customer->registration_number }}
                    </x-invoice-grid-item>
                @else
                    <x-invoice-grid-item>&nbsp;</x-invoice-grid-item>
                @endif
                @if($invoice->contract->currency !== CurrencyEnum::CZK)
                    <x-invoice-grid-item class="font-bold">
                        {{ __('base.vat') }}: {{ $invoice->contract->customer->vat_number }}
                    </x-invoice-grid-item>
                @endif
            </div>
        </x-invoice-grid-panel>

        <!-- PAYMENT ADVICE -->
        <x-invoice-grid-panel border="border-orange-200" class="bg-orange-200">
            <x-slot:title>{{ __('base.payment_advice') }}</x-slot:title>

            <!-- BANK NAME -->
            <x-invoice-grid-item>
                <div class="flex justify-between w-full">
                    <span>{{ __('base.bank_name') }}:</span>
                    <span>{{ $invoice->contract->supplier->bankAccount->bank_name }}</span>
                </div>
            </x-invoice-grid-item>

            <!-- ACCOUNT NUMBER -->
            <x-invoice-grid-item>
                <div class="flex justify-between w-full">
                    <span>{{ __('base.bank_account') }}:</span>
                    <span>{{ $invoice->contract->supplier->bankAccount->account_number }} / {{ $invoice->contract->supplier->bankAccount->bank_code }}</span>
                </div>
            </x-invoice-grid-item>

            <!-- IBAN -->
            <x-invoice-grid-item>
                <div class="flex justify-between w-full">
                    <span>{{ __('base.iban') }}:</span>
                    <span>{{ $invoice->contract->supplier->bankAccount->iban }}</span>
                </div>
            </x-invoice-grid-item>

            <!-- SWIFT -->
            <x-invoice-grid-item>
                <div class="flex justify-between w-full">
                    <span>{{ __('base.swift') }}:</span>
                    <span>{{ $invoice->contract->supplier->bankAccount->swift }}</span>
                </div>
            </x-invoice-grid-item>

            <!-- PAYMENT REFERENCE ID -->
            <x-invoice-grid-item>
                <div class="flex justify-between w-full">
                    <span>{{ __('base.reference_id') }}:</span>
                    <span>{{ $invoice->number }}</span>
                </div>
            </x-invoice-grid-item>
        </x-invoice-grid-panel>

        <!-- DATES -->
        <x-invoice-grid-panel border="border-orange-200" class="bg-orange-200">
            <x-slot:title>{{ __('base.dates') }}</x-slot:title>

            <!-- ISSUE DATE -->
            <x-invoice-grid-item>
                <div class="flex justify-between w-full">
                    <span>{{ __('base.issue_date') }}:</span>
                    <span>{{ $invoice->issue_date->format('d.m.Y') }}</span>
                </div>
            </x-invoice-grid-item>

            <!-- DUE DATE -->
            <x-invoice-grid-item>
                <div class="flex justify-between w-full">
                    <span>{{ __('base.due_date') }}:</span>
                    <span>{{ $invoice->due_date->format('d.m.Y') }}</span>
                </div>
            </x-invoice-grid-item>
        </x-invoice-grid-panel>

        <!-- CONTENT -->
        <x-invoice-grid-panel border="border-slate-300" span="col-span-2" class="bg-slate-300">
            <x-slot:title>
                <div class="grid grid-cols-12 divide-x divide-white">
                    <div class="col-span-9">{{ __('base.description') }}</div>
                    <div class="px-1 border-x border-slate-300 text-center">{{ __('base.unit_price') }}</div>
                    <div class="px-1 border-r border-slate-300 text-center">{{ __('base.quantity') }}</div>
                    <div class="text-right">{{ __('base.amount') }}</div>
                </div>
            </x-slot:title>

            <!-- CONTRACT STATEMENT -->
            <x-invoice-grid-content>
                <x-slot:item>
                    <div class="mr-4 text-sm italic">
                        {{ __('base.invoice_content_note', ['contract_signed' => $invoice->contract->signed_at->format('d.m.Y')]) }}
                    </div>
                </x-slot:item>
            </x-invoice-grid-content>

            <x-invoice-grid-content>
                <x-slot:item>&nbsp;</x-slot:item>
            </x-invoice-grid-content>

            @foreach($invoice->content as $content)
                <x-invoice-grid-content class="odd:bg-slate-50/50">
                    <x-slot:item>
                        @if($content['url'])
                            <a href="{{ $content['url'] }}" target="_blank"
                               class="underline text-blue-500">{{ $content['name'] }}</a>
                        @else
                            {{ $content['name'] }}
                        @endif
                    </x-slot:item>
                    <x-slot:price>{{ Number::format($invoice->contract->price_per_hour, 2) }} {{ $invoiceCurrencySymbol }}</x-slot:price>
                    <x-slot:quantity>{{ Number::format($content['hours'], 1) }}</x-slot:quantity>
                    <x-slot:subTotal>{{ Number::format($content['amount'], 2) }} {{ $invoiceCurrencySymbol }}</x-slot:subTotal>
                </x-invoice-grid-content>
            @endforeach

            <!-- SUBTOTAL -->
            <x-slot:footer>
                <div class="grid grid-cols-12 divide-x divide-white">
                    <div class="col-span-9">{{ __('base.subtotal') }}</div>
                    <div class="col-span-1"></div>
                    <div class="border-x border-slate-300 px-1 text-right pr-4">
                        {{ Number::format($invoiceTotalHours, 1) }}
                    </div>
                    <div class="text-right normal-case">
                        {{ Number::format($invoiceTotalAmount, 2) }} {{ $invoiceCurrencySymbol }}
                    </div>
                </div>
            </x-slot:footer>
        </x-invoice-grid-panel>
    </div>

    <!-- TOTAL -->
    <div class="flex justify-end mr-4 py-4">
        <div class="font-bold text-5xl border-4 border-slate-500 rounded p-4 text-right bg-slate-100">
            <span class="uppercase">{{ __('base.total') }}: {{ Number::format($invoiceTotalAmount, 2) }}</span>
            {{ $invoiceCurrencySymbol }}
        </div>
    </div>
</x-report-layout>
