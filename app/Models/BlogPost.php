<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    protected $table = 'blog_posts';

    protected $fillable = [
        'soro_guid',
        'title',
        'slug',
        'description',
        'content',
        'featured_image',
        'soro_url',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
