<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anhsanpham extends Model
{
    use HasFactory;
    protected $fillable = [
        'sanpham_id',
        'url',
        'order'
    ];
    // Quan hệ với bảng Sanpham
    public function sanpham()
    {
        return $this->belongsTo(Sanpham::class);
    }
}
