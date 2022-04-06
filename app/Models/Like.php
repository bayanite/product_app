<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    public $table = "likes";

    public $fillable = [

        'is_like', 'user_id', 'product_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {

        return $this->belongsTo(User::class)->select(['id', 'name']);
    }
    protected $hidden = [
        'updated_at', 'created_at',
    ];
}
