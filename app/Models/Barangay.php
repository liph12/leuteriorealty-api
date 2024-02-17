<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\City;

class Barangay extends Model
{
    use HasFactory;
    protected $table = "tbl_barangay";

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function scopeGetMunicipalityBrgy($query, $id)
    {
        $query->where('city_id', $id);
    }
}
