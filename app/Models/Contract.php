<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\KeyValueOptions;
use App\Enums\CurrencyEnum;
use App\Traits\HasCurrentUserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model implements KeyValueOptions
{
    use HasCurrentUserScope, HasFactory;

    protected $guarded = [];

    protected $casts = [
        'signed_at' => 'date',
        'currency' => CurrencyEnum::class,
    ];

    /**
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return self::currentUser()
            ->orderBy('name')
            ->get()
            ->keyBy('id')
            ->map(fn (Contract $contract) => $contract->name)
            ->toArray();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
