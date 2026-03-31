<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enum\DocumentStatus;

class UserDetail extends Model
{
    protected $fillable = [
        'user_id',
        'passport',
        'passport_number',
        'passport_status',
        'international_lic',
        'international_lic_back',
        'international_lic_status',
        'international_lic_back_status',
        'regular_lic',
        'regular_lic_back',
        'regular_lic_status',
        'regular_lic_back_status',
        'status',
        'experience',
        'bike_ridden',
        'regular_lic_number',
        'idp_number',
    ];

    protected $casts = [
        'status' => DocumentStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPassportAttribute($value)
    {
        return $value ? asset(USER_DOCUMENT_PATH . $value) : asset('uploads/user_documents/default.jpg');
    }

    public function getInternationalLicAttribute($value)
    {
        return $value ? asset(USER_DOCUMENT_PATH . $value) : asset('uploads/user_documents/default.jpg');
    }

    public function getRegularLicAttribute($value)
    {
        return $value ? asset(USER_DOCUMENT_PATH . $value) : asset('uploads/user_documents/default.jpg');
    }

    public function getRegularLicBackAttribute($value)
    {
        return $value ? asset(USER_DOCUMENT_PATH . $value) : asset('uploads/user_documents/default.jpg');
    }

    public function getInternationalLicBackAttribute($value)
    {
        return $value ? asset(USER_DOCUMENT_PATH . $value) : asset('uploads/user_documents/default.jpg');
    }
}
