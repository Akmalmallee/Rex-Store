<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FittingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'product_id', 'size_recommended', 'fit_score',
        'recommendations', 'user_feedback', 'status',
    ];

    protected function casts(): array
    {
        return [
            'fit_score' => 'decimal:2',
            'recommendations' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
