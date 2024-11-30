<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sanpham extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'slug',
        'price_sale',
        'category_id',
        'quantity_in_stock',
        'mota',
        'trangthai',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(Anhsanpham::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Chitietgiohang::class);
    }

    public function orderItems()
    {
        return $this->hasMany(Chitietdonhang::class);
    }


}

