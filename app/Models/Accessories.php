<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accessories extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        'price',
        'additional_day_price',
        'type',
        'position',
        'icon'
    ];
}
