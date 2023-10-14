<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, foreignKey: 'address_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyDetail::class, foreignKey: 'company_detail_id');
    }
}
