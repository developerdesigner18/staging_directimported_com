<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;   // IMPORTANT
use Spatie\Permission\Traits\HasRoles;

class Employee extends Authenticatable
{
    use HasRoles;

    protected $guard_name = 'employee';

    protected $fillable = [
        'first_name',
        'last_name',
        'lastname',
        'email',
        'password',
        'plain_password',
    ];

    protected $hidden = [
        'password',
        'plain_password',
    ];
}
