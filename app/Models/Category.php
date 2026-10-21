<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\CategoryType;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'type',
        'name',
        'image',
    ];

    protected $casts = [
        'type' => \App\Enum\CategoryType::class,
    ];


    public function scopeGalleryCategory($query)
    {
        return $query->where('type', CategoryType::GALLERY);
    }

    public function scopeCarCategory($query)
    {
        return $query->where('type', CategoryType::CAR);
    }


}
