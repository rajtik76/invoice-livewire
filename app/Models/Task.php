<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasCurrentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasCurrentUser, HasFactory;

    protected $guarded = [];

    public function taskHour(): HasMany
    {
        return $this->hasMany(TaskHour::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
}
