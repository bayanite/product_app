<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;

    public $table = "products";

    public $fillable = [
        'name', 'price', 'quantity',
        'expire_date', 'description',
        'url_img', 'user_id', 'category_id', 'views'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->select(['name', 'id']);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class, 'product_id')->orderBy('date');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'product_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'product_id');
    }

    public $withCount = ['comments', 'likes'];

    public $primaryKey = 'id';

    public $timestamps = true;

    protected $hidden = [
        'password', 'remember_token',
        'updated_at', 'created_at',

    ];
}
