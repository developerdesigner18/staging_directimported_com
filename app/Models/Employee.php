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
        'lastname',
        'email',
        'password',
    ];
}
