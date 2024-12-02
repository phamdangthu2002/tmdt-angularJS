<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donhang extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'trangthai_id',
        'price',
        'ngaydathang',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trangthai()
    {
        return $this->belongsTo(Trangthai::class);
    }

    public function items()
    {
        return $this->hasMany(Chitietdonhang::class);
    }
}
