<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    use HasFactory;
    protected $table = "projects";
    protected $fillable = [
        'devid',
        'name',
        'prop_type_id',
        'location',
        'provid',
        'citymunid',
        'brgyid',
        'transtype',
        'based',
        'price',
        'status',
        'hlurb',
        'longitude',
        'latitude',
        'dateUpdated',
        'updatedBy',
        'pks_link',
        'created_at',
        'updated_at',
    ];
}
