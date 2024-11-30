<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'trangthai',
        'ngaysinh',
        'gioitinh',
        'phone',
        'diachi',
    ];

    /**
     * Quan hệ với bảng giỏ hàng.
     */
    public function cart()
    {
        return $this->hasOne(Giohang::class);
    }

    /**
     * Quan hệ với bảng đơn hàng.
     */
    public function orders()
    {
        return $this->hasMany(Donhang::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Phương thức của JWTSubject để lấy ID của user.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Trả về khóa chính của user (ID)
    }

    /**
     * Phương thức của JWTSubject để thêm claims vào JWT.
     */
    public function getJWTCustomClaims()
    {
        return []; // Có thể thêm thông tin tùy chỉnh nếu cần
    }
}
