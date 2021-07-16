<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price', 'stock', 'image'
    ];

    protected $attributes = [
        'stock' => null,
    ];

    public function isUnlimitedStock()
    {
        return is_null($this->stock);
    }

}
