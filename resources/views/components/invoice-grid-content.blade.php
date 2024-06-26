<x-invoice-grid-item class="{{ $attributes['class'] }}">
    <div class="grid grid-cols-12">
        <div class="col-span-9">
            {{ $item }}
        </div>

        @isset($price)
            <div class="px-1 text-right pr-4">
                {{ $price }}
            </div>
        @endisset

        @isset($quantity)
            <div class="px-1 border-x border-slate-300 text-right pr-4">
                {{ $quantity }}
            </div>
        @endisset

        @isset($subTotal)
            <div class="text-right">
                {{ $subTotal }}
            </div>
        @endisset
    </div>
</x-invoice-grid-item>
