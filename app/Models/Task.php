<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\KeyValueOptions;
use App\Traits\HasActiveScope;
use App\Traits\HasCurrentUserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model implements KeyValueOptions
{
    use HasCurrentUserScope, HasFactory, HasActiveScope;

    protected $guarded = [];

    public function taskHour(): HasMany
    {
        return $this->hasMany(TaskHour::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /** @return array<int, string> */
    public static function getOptions(): array
    {
        return self::currentUser()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }
}
