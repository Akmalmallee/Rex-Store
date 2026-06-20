<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'product_ids', 'reason', 'score', 'type', 'is_dismissed',
    ];

    protected function casts(): array
    {
        return [
            'product_ids' => 'array',
            'score' => 'decimal:2',
            'is_dismissed' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
