<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'short_description',
        'icon',
        'description',
        'images',
        'image_badge',
        'features',
        'sort_order',
    ];

    protected $casts = [
        'images' => 'array',
        'features' => 'array',
    ];

    /**
     * Services in the order defined from the admin panel.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * URL of the first service image, or null when missing on disk.
     */
    public function getImageUrlAttribute(): ?string
    {
        return self::uploadUrl($this->images[0] ?? null);
    }

    /**
     * URL of the homepage tile icon, or null when missing on disk.
     */
    public function getIconUrlAttribute(): ?string
    {
        return self::uploadUrl($this->icon);
    }

    /**
     * Text for the homepage tile, falling back to the full description.
     */
    public function getExcerptAttribute(): string
    {
        if (filled($this->short_description)) {
            return $this->short_description;
        }

        return Str::limit(trim(html_entity_decode(strip_tags((string) $this->description))), 180);
    }

    private static function uploadUrl(?string $file): ?string
    {
        if (blank($file) || !file_exists(public_path(SERVICE_PATH . $file))) {
            return null;
        }

        return asset(SERVICE_PATH . $file);
    }
}
