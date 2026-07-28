<?php

namespace App\Models;

use App\Enum\VehicleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\CategoryType;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enum\AccessoryType;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sort_order',
        'manufacturer_id',
        'model',
        'year',
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
        'address',
        'is_recommended',
        'location',
        'free_accessory',
        'extra_accessory',
        'number_plate',
        'vehicle_id',
        'status',
        'auction_grade_id',
        'vehicle_price',
        'vin',
        'drive_type',
        'steering',
        'private_notes',
    ];

    public function getNameAttribute()
    {
        $dynamicName = trim(($this->manufacturer->name ?? '') . ' ' . $this->model . ' ' . $this->year);
        return $dynamicName ?: ($this->attributes['name'] ?? '');
    }

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

        'status' => VehicleStatus::class,
    ];


    public function spec()
    {
        return $this->hasOne(CarSpec::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')
            ->where('type', CategoryType::CAR);
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
        // Hidden based on client request.
        // Fallback check: find the first non-zero/non-null price among the tiered pricing fields
        $prices = [
            (float) $this->less_four_days_price,
            (float) $this->five_six_days_price,
            (float) $this->week_price,
            (float) $this->month_price,
            (float) $this->max_price
        ];

        $fallbackPrice = 0.0;
        foreach ($prices as $p) {
            if ($p > 0) {
                $fallbackPrice = $p;
                break;
            }
        }

        if ($totalDays <= 4) {
            $price = (float) $this->less_four_days_price;
        } elseif ($totalDays <= 7) {
            $price = (float) $this->five_six_days_price;
        } elseif ($totalDays <= 29) {
            $price = (float) $this->week_price;
        } else {
            $price = (float) $this->month_price;
        }

        return $price > 0 ? $price : $fallbackPrice;
    }

    public function auctionGrade()
    {
        return $this->belongsTo(AuctionGrade::class, 'auction_grade_id');
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

}
