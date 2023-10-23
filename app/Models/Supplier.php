<?php

namespace App\Models;

use App\Traits\HasCurrentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier extends Model
{
    use HasCurrentUser, HasFactory;

    protected $guarded = [];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    /**
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return self::currentUser()
            ->orderBy('name')
            ->get()
            ->keyBy('id')
            ->map(fn (Supplier $supplier) => $supplier->name)
            ->toArray();
    }
}
