<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enums\BankAccountCurrencyEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'currency' => BankAccountCurrencyEnum::class,
        'issue_date' => 'date',
        'due_date' => 'date',
        'content' => 'array',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
}
