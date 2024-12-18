<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Giohang extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'trangthai',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(Chitietgiohang::class);
    }
}
