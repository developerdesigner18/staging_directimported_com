<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\CategoryType;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tour extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'category_id',
        'slug',
        'price',
        'start_date',
        'end_date',
        'length',
        'daily_rides',
        'rest_days',
        'images',
        'description',
    ];

    protected $casts = [
        'price' => 'float',
        'start_date' => 'date',
        'end_date' => 'date',
        'images' => 'array',
        'description' => 'string',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')
            ->where('type', CategoryType::TOUR);
    }
}
