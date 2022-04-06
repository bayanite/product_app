<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $table = "categories";

    public $fillable = [
        'name','id'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public $primaryKey = 'id';
    public $timestamps = true;

    protected $hidden = [
        'updated_at', 'created_at',

    ];
    /**
     * @var mixed
     */
}
