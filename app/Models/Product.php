<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'category',
        'price',
        'quantity',
        'image',
        'discount',
        'description'
    ];
    public function categoryRelation()
    {
        return $this->belongsTo(Category::class, 'category');
    }
}
