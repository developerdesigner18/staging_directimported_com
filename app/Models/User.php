<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'date_of_birth',
        'mobile',
        'profile_img',
        'address',
        'country',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    function getProfileImgAttribute($image)
    {
        $outputImage = asset('assets/admin/images/users/avatar-9.jpg');

        if ($image && $image != null && $image != '') {
            if (file_exists(public_path(USER_PROFILE_IMAGE_PATH) . $image)) {
                $outputImage = asset(USER_PROFILE_IMAGE_PATH . $image);
            }
        }

        return $outputImage;
    }

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id', 'id');
    }
}
