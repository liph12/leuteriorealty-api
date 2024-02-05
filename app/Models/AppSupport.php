<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSupport extends Model
{
    use HasFactory;
    protected $table = "app_support";
    protected $fillable = [
        'id',
        'memberid',
        'name',
        'email',
        'concern',
        'image',
        'status',
        'responses',
    ];
}
