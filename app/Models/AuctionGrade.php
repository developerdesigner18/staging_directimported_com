<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class AuctionGrade extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'grade',
        'remarks',
    ];


}
