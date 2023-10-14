<?php

namespace App\Models\Enums;

enum BankAccountCurrencyEnum: string
{
    case EUR = 'EUR';
    case CZK = 'CZK';

    public function symbol(): string
    {
        return match ($this) {
            self::EUR => '€',
            self::CZK => 'Kč',
        };
    }
}
