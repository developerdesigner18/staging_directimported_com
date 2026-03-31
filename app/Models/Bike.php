<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\CategoryType;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enum\AccessoryType;

class Bike extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sort_order',
        'name',
        'location_id',
        'slug',
        'category_id',
        'less_four_days_price',
        'five_six_days_price',
        'week_price',
        'month_price',
        'max_price',
        'insurance_price',
        'images',
        'banner',
        'description',
        'engine',
        'power',
        'seat_height',
        'weight',
        'tank_capacity',
        'luggage',
        'address',
        'is_recommended',
        'location',
        'free_accessory',
        'extra_accessory',
        'number_plate'
    ];

    protected $casts = [
        'less_four_days_price' => 'float',
        'five_six_days_price' => 'float',
        'week_price' => 'float',
        'month_price' => 'float',
        'max_price' => 'float',
        'insurance_price' => 'float',
        'images' => 'array',
        'description' => 'string',
        'tec_spec' => 'string',
        'free_accessory' => 'array',
        'extra_accessory' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')
            ->where('type', CategoryType::BIKE);
    }

//    public function freeAccessories()
//    {
//        return Accessories::whereIn('id', $this->free_accessory??[])->get();
//    }

    public function freeAccessories()
    {
        return Accessories::whereIn('id', $this->free_accessory ?? [])->get();
    }

    public function extraAccessories()
    {
        return Accessories::whereIn('id', $this->extra_accessory ?? [])->get();
    }

    public function map()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function getTieredPrice($totalDays): float
    {
        if ($totalDays <= 4) {
            return (float) $this->less_four_days_price;
        } elseif ($totalDays <= 7) {
            return (float) $this->five_six_days_price;
        } elseif ($totalDays <= 29) {
            return (float) $this->week_price;
        } else {
            return (float) $this->month_price;
        }
    }

}
