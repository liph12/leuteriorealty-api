<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcements extends Model
{
    use HasFactory;
    protected $table = "app_announcements";
    protected $fillable = [
        'id',
        'type',
        'header',
        'date_expiry',
        'time_expiry',
        'memberid',
        'completename',
        'settings',
    ];
}
