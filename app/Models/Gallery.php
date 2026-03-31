<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\CategoryType;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category_id',
        'image',
    ];

    protected $casts = [
        'title' => 'string',
        'image' => 'string',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')
            ->where('type', CategoryType::GALLERY);
    }

    public function getImageAttribute($value)
    {
        return $value ? asset(GALLERY_PATH . $value) : null;
    }
}
