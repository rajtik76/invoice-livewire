<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\KeyValueOptions;
use App\Enums\CountryEnum;
use App\Traits\HasCurrentUserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model implements KeyValueOptions
{
    use HasCurrentUserScope, HasFactory;

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

    /**
     * Get key => value address options for current user
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return self::currentUser()
            ->orderBy('country')
            ->orderBy('city')
            ->orderBy('street')
            ->get()
            ->keyBy('id')
            ->map(fn (Address $address) => "{$address->street}, {$address->zip} {$address->city}, {$address->country->countryName()}")
            ->all();
    }
}
