<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chitietgiohang extends Model
{
    use HasFactory;
    protected $fillable = [
        'giohang_id',
        'sanpham_id',
        'quantity',
        'ram',
        'rom',
        'color',
    ];
    public function cart()
    {
        return $this->belongsTo(Giohang::class);
    }

    public function product()
    {
        return $this->belongsTo(Sanpham::class,'sanpham_id', 'id');
    }
}
