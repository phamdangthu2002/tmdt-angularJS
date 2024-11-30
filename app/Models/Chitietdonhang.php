<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chitietdonhang extends Model
{
    use HasFactory;
    public function order()
    {
        return $this->belongsTo(Donhang::class);
    }

    public function product()
    {
        return $this->belongsTo(Sanpham::class);
    }
}
