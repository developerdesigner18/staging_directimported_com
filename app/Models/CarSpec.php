<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CarSpec extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'car_id',
        'make',
        'exterior_color',
        'body_type',
        'fuel_type',
        'fuel_type_custom',
        'engine',
        'odometer',
        'model_year',
        'interior_color',
        'transmission',
        'transmission_custom',
        'vin',
        'drive_type',
        'steering',
    ];
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    protected $casts = [
        'odometer' => 'integer',
        'model_year' => 'integer',
    ];

    public function getFormattedFuelTypeAttribute()
    {
        $custom = trim($this->fuel_type_custom ?? '');
        $preset = trim($this->fuel_type ?? '');

        if ($custom !== '' && $preset !== '') {
            return $custom . ' ' . $preset;
        }
        if ($custom !== '') {
            return $custom;
        }
        return $preset;
    }

    public function getFormattedTransmissionAttribute()
    {
        $custom = trim($this->transmission_custom ?? '');
        $preset = trim($this->transmission ?? '');

        if ($custom !== '' && $preset !== '') {
            return $custom . ' ' . $preset;
        }
        if ($custom !== '') {
            return $custom;
        }
        return $preset;
    }
}
