<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes,HasRoles;

    protected $guard_name = 'admin';

    protected $fillable = ['name', 'email', 'password', 'profile_img'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    function getProfileImgAttribute($image)
    {
        $outputImage = asset('assets/admin/images/users/avatar-9.jpg');

        if ($image && $image != null && $image != '') {
            if (file_exists(public_path(ADMIN_PROFILE_IMAGE_PATH) . $image)) {
                $outputImage = asset(ADMIN_PROFILE_IMAGE_PATH . $image);
            }
        }

        return $outputImage;
    }
}
