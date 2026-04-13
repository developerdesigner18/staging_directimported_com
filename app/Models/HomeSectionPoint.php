<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class HomeSectionPoint extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'home_section_id',
        'point_text',
    ];

    public function homeSection()
    {
        return $this->belongsTo(HomeSection::class);
    }
}
