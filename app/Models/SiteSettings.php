<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    protected $fillable = [
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'youtube_url',
        'logo',
        'admin_logo',
        'footer_logo',
        'favicon',
        'services_page_badge',
        'services_page_title',
        'services_page_description',
    ];
}
