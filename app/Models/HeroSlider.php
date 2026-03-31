<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HeroSlider extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'title',
        'image',
        'description',
        'href',
        'button',
    ];

    public function getImageAttribute($image)
    {
        $outputImage = null;

        if ($image && $image != null && $image != '') {
            if (file_exists(public_path(SLIDER_PATH) . $image)) {
                $outputImage = asset(SLIDER_PATH . $image);
            }
        }

        return $outputImage;
    }
}
