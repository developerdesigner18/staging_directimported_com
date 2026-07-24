<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminLabel extends Model
{
    use SoftDeletes;

    protected $table = 'admin_labels';

    protected $fillable = [
        'page',
        'key',
        'value'
    ];
}
