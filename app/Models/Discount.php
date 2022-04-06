<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    public $table = 'discounts';

    public $timestamps = true;

    public $fillable = [

        'discount_percentage', 'date', 'product_id'

    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    protected $hidden = [
        'updated_at', 'created_at',
    ];
}
