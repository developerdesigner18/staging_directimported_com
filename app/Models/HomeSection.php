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
    ];

    public function points()
    {
        return $this->hasMany(HomeSectionPoint::class);
    }
}
