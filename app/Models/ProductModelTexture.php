<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModelTexture extends Model
{
    protected $fillable = [
        'product_model_id',
        'product_color_id',
        'texture_file',
        'texture_type',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    public function productModel()
    {
        return $this->belongsTo(ProductModel::class);
    }

    public function productColor()
    {
        return $this->belongsTo(ProductColor::class);
    }
}
