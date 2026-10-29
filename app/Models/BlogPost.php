<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blog_posts';

    protected $fillable = [
        'soro_guid',
        'title',
        'slug',
        'description',
        'content',
        'featured_image',
        'soro_url',
        'category',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Scope a query to only include published blog posts for sitemap.
     */
    public function scopePublishedForSitemap($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->whereNotNull('slug')
            ->where('slug', '!=', '');
    }
}
