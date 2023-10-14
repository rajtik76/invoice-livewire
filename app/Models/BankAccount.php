<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enums\BankAccountCurrencyEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'currency' => BankAccountCurrencyEnum::class,
    ];
}
