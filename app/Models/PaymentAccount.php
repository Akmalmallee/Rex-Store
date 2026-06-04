<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentAccount extends Model
{
    use HasFactory;

    protected $fillable = ['method', 'account_name', 'account_number', 'instructions', 'active'];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
