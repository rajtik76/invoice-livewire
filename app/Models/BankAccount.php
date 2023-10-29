<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\KeyValueOptions;
use App\Traits\HasCurrentUserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model implements KeyValueOptions
{
    use HasCurrentUserScope, HasFactory;

    protected $fillable = [
        'user_id',
        'bank_name',
        'account_number',
        'bank_code',
        'iban',
        'swift',
    ];

    /**
     * Key => value options for bank account
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return self::currentUser()
            ->orderBy('bank_name')
            ->orderBy('account_number')
            ->get()
            ->keyBy('id')
            ->map(fn (BankAccount $account) => "{$account->account_number}/{$account->bank_code} - {$account->bank_name}")
            ->toArray();
    }
}
