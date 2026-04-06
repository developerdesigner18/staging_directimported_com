<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'images',
        'sort_order',
    ];

    protected $casts = [
        'images' => 'array',
    ];
}
