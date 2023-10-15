<?php

namespace App\Models;

use App\Enums\CurrencyEnum;
use App\Traits\HasCurrentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasCurrentUser, HasFactory;

    protected $guarded = [];

    protected $casts = [
        'signed_at' => 'date',
        'currency' => CurrencyEnum::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
