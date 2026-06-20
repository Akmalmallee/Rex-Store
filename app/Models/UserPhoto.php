<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'photo_path', 'body_analysis', 'is_active'];

    protected function casts(): array
    {
        return [
            'body_analysis' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
