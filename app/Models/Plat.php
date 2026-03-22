<?php

namespace App\Models;

use Attribute;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'user_id',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function image()
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::url($value) : null 
        );
    }

    public function category()
    {
        return $this->belongsToMany(Category::class);
    }
}