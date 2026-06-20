<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BodyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'height', 'weight', 'body_type', 'preferred_size',
        'shoulder_width', 'chest_circumference', 'waist_circumference',
        'hip_circumference', 'inseam',
    ];

    protected function casts(): array
    {
        return [
            'height' => 'decimal:1',
            'weight' => 'decimal:1',
            'shoulder_width' => 'decimal:1',
            'chest_circumference' => 'decimal:1',
            'waist_circumference' => 'decimal:1',
            'hip_circumference' => 'decimal:1',
            'inseam' => 'decimal:1',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
