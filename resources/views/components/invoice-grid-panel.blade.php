<div class="border-4 rounded {{ $attributes['border'] }} {{ $attributes['span'] }}">
    <div {{ $attributes->merge(['class' => 'uppercase px-4 py-1']) }}>
        {{ $title }}
    </div>

    <div class="py-2">
        {{ $slot }}
    </div>

    @isset($footer)
        <div {{ $attributes->merge(['class' => 'uppercase px-4 py-1']) }}>
            {{ $footer }}
        </div>
    @endisset
</div>
