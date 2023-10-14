<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'signed_at' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, foreignKey: 'customer_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, foreignKey: 'supplier_id');
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, foreignKey: 'bank_account_id');
    }
}
