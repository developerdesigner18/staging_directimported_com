<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\BookingStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bookings';
    protected $fillable = [
        'user_id',
        'booking_id',
        'bike_id',
        'start_date',
        'end_date',
        'total_days',
        'email',
        'first_name',
        'last_name',
        'start_time',
        'end_time',
        'location',
        'policy_status',
        'comment',
        'status',
        'selected_accessories',
        'included_accessories',
        'email_comment',
        'system_comment',
        'final_comment',
        'payment_link_status',
        'booking_detail_sent_status',
        'price',
        'insurance',
        'insurance_price',
        'table_data',
        'send_payment_link',
           'send_booking_detail',
            'send_login_detail',
           'send_document_verified'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',

        'policy_status' => 'boolean',
        'payment_link_status' => 'boolean',
        'booking_detail_sent_status' => 'boolean',
        'selected_accessories' => 'array',
        'included_accessories' => 'array',
        'comment' => 'string',
        'email_comment' => 'string',
        'system_comment' => 'string',
        'final_comment' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bike()
    {
        return $this->belongsTo(Bike::class);
    }

    public function selectedAccessoriesList()
    {
//        return Accessories::whereIn('id', $this->selected_accessories??[])->get();

        $ids = $this->selected_accessories ?? [];
        if (empty($ids)) return collect();

        $totalDays = $this->totalDays();

        return Accessories::whereIn('id', $ids)->get()->map(function ($acc) use ($totalDays) {
            // Tiered pricing: additional_day_price for day 2+
            if ($totalDays > 1 && $acc->additional_day_price) {
                $price = $acc->price + ($acc->additional_day_price * ($totalDays - 1));
            } else {
                $price = $acc->price * ($totalDays > 0 ? $totalDays : 1);
            }

            // Helmet price cap
            if (\Illuminate\Support\Str::contains(strtolower($acc->name), 'helmet') && $price >= 6500) {
                $price = 6500;
            }

            // Attach computed price as a virtual attribute
            $acc->computed_price = $price;
            return $acc;
        });

    }

    public function totalDays(): int
    {
        return totalBookingDays($this->start_date, $this->end_date, $this->end_time);
    }

    public function includedAccessoriesList()
    {
        return Accessories::whereIn('id', $this->included_accessories??[])->get();
    }

    public function getStatusAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }

        return BookingStatus::tryFrom(strtolower((string)$value)) ?? BookingStatus::tryFrom((string)$value);
    }

    public function setStatusAttribute($value)
    {
        if ($value instanceof BookingStatus) {
            $this->attributes['status'] = $value->value;
        } else {
            $this->attributes['status'] = strtolower((string)$value);
        }
    }
}
