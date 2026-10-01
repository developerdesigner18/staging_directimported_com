<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class HomeSection extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'short_description',
        'about_badge',
        'about_title',
        'about_intro',
        'about_button_text',
        'about_hero_image',
        'story_images',
        'founded_title',
        'founded_content',
        'advantage_title',
        'advantage_content',
        'operations_title',
        'operations_subtitle',
        'operations',
        'facts_title',
        'facts',
        'passion_title',
        'passion_intro',
        'passion_cards',
        'passion_button_text',
        'services_title',
        'services_items',
    ];

    protected $casts = [
        'story_images' => 'array',
        'operations' => 'array',
        'facts' => 'array',
        'passion_cards' => 'array',
        'services_items' => 'array',
    ];

    public function points()
    {
        return $this->hasMany(HomeSectionPoint::class);
    }

    /**
     * URL of an About Us page image, or null when missing on disk.
     */
    public static function imageUrl(?string $file): ?string
    {
        if (blank($file) || !file_exists(public_path(ABOUT_US_PATH . $file))) {
            return null;
        }

        return asset(ABOUT_US_PATH . $file);
    }
}
