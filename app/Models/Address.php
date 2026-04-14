<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class Address extends Model
{
    protected $fillable = [
        'province_id',
        'city_id',
        'district_id',
        'village_id',
        'address_detail',
        'postal_code',
        'latitude',
        'longitude',
    ];

    public function addressable()
    {
        return $this->morphTo();
    }

    // Relasi ke tabel wilayah Indonesia
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id');
    }

    // Accessor untuk mendapatkan nama wilayah
    public function getProvinceNameAttribute()
    {
        return $this->province ? $this->province->name : null;
    }

    public function getCityNameAttribute()
    {
        return $this->city ? $this->city->name : null;
    }

    public function getDistrictNameAttribute()
    {
        return $this->district ? $this->district->name : null;
    }

    public function getVillageNameAttribute()
    {
        return $this->village ? $this->village->name : null;
    }

    /**
     * Check if address has valid coordinates (latitude and longitude)
     * 
     * @return bool
     */
    public function hasCoordinates(): bool
    {
        return !is_null($this->latitude) 
            && !is_null($this->longitude)
            && is_numeric($this->latitude)
            && is_numeric($this->longitude)
            && $this->latitude != 0
            && $this->longitude != 0;
    }

    /**
     * Get full address as string
     * 
     * @return string
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_detail,
            $this->village_name,
            $this->district_name,
            $this->city_name,
            $this->province_name,
            $this->postal_code,
        ]);

        return implode(', ', $parts);
    }
}