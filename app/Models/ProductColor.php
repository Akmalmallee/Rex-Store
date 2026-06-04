<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductColor extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'color', 'color_code', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
