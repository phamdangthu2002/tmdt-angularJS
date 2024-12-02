<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment_information extends Model
{
    protected $fillable = [
        'user_id',
        'phuongthuc',
        'trangthai',
        'transaction_id ',
        'tong',
    ];
    use HasFactory;
}
