<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    use HasFactory;
    protected $table = "developers";
    protected $fillable = [
        'name',
        'street',
        'provid',
        'citymunid',
        'brgyid',
        'contactperson',
        'email',
        'position',
        'mobile',
        'landline',
        'remarks',
        'isbroker',
        'deleted',
        'userid',
        'approved',
        'updated',
        'image_path',
        'developer_link',
        'latitude',
        'longitude',
    ];
}
