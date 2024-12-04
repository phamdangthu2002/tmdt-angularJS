<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chitietdonhang extends Model
{
    use HasFactory;
    protected $fillable = [
        'donhang_id',
        'sanpham_id',
        'quantity',
        'price'

    ];
    public function order()
    {
        return $this->belongsTo(Donhang::class);
    }

    public function product()
    {
        return $this->belongsTo(Sanpham::class, 'sanpham_id');
    }
}
