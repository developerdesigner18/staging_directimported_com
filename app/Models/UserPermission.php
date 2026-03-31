<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $table = 'permissions'; // table name (optional if default)

    protected $fillable = [
        'key',       // programmatic identifier
        'label',     // admin friendly name
        'allowed',   // boolean, visible for all users
    ];

    protected $casts = [
        'allowed' => 'boolean',
    ];

}
