<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CountryEnum;
use App\Traits\HasCurrentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasCurrentUser, HasFactory;

    protected $fillable = [
        'user_id',
        'street',
        'city',
        'zip',
        'country',
    ];

    protected $casts = [
        'country' => CountryEnum::class,
    ];
}
