<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasCurrentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasCurrentUser, HasFactory;

    protected $fillable = [
        'user_id',
        'bank_name',
        'account_number',
        'bank_number',
        'iban',
        'swift',
    ];
}
