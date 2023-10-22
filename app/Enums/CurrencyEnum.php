<?php

declare(strict_types=1);

namespace App\Enums;

enum CurrencyEnum: string
{
    case EUR = 'EUR';
    case CZK = 'CZK';

    public static function getOptions(): array
    {
        return collect(self::cases())
            ->map(fn (CurrencyEnum $currency) => [
                'key' => $currency->value,
                'value' => $currency->value,
            ])
            ->toArray();
    }
}
