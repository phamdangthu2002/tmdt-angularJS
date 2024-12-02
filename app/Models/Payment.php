<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'donhang_id',
        'phuongthuc',
        'trangthai',
        'tong',
    ];
    use HasFactory;
}
