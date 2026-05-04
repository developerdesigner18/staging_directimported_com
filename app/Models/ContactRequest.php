<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'preferred_contact_method',
        'vehicle_id',
        'destination_country',
        'nearest_port_or_postal_code',
        'message',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class, 'vehicle_id', 'id');
    }
}
