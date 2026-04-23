<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CarSpec extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'bike_id',
        'engine',
        'power',
        'seat_height',
        'weight',
        'tank_capacity',
        'luggage',
        'odometer',
        'model_year',
        'interior_color',
        'transmission',
    ];
    public function car()
    {
        return $this->belongsTo(Bike::class);
    }
    protected $casts = [
        'odometer' => 'integer',
        'model_year' => 'integer',
    ];
}
