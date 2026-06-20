<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $fillable = [
        'product_id',
        'model_file',
        'thumbnail',
        'scale_x',
        'scale_y',
        'scale_z',
        'position_x',
        'position_y',
        'position_z',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'scale_x' => 'decimal:4',
            'scale_y' => 'decimal:4',
            'scale_z' => 'decimal:4',
            'position_x' => 'decimal:4',
            'position_y' => 'decimal:4',
            'position_z' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function textures()
    {
        return $this->hasMany(ProductModelTexture::class);
    }

    public function defaultTexture()
    {
        return $this->hasOne(ProductModelTexture::class)->where('is_default', true);
    }
}
