<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'url_facebook',
        'mobil_phone', 'password',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public $primaryKey = 'id';

    protected $hidden = [
        'password', 'remember_token',
        'updated_at', 'created_at', 'email_verified_at'

    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
