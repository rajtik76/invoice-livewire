<?php

namespace App\Enums;

enum CountryEnum: string
{
    case Germany = 'DE';
    case Czech = 'CZ';

    public function countryName(): string
    {
        return match ($this) {
            self::Germany => 'Germany',
            self::Czech => 'Czech Republic',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->keyBy(fn(self $country) => $country->value)
            ->map(fn(self $country) => $country->countryName())
            ->toArray();
    }
}
