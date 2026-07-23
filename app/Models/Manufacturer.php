<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manufacturer extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'manufacturers';

    protected $fillable = [
        'name',
    ];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
