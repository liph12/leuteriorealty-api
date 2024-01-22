<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;
    protected $table = "settings";

    public function scopeAdminAccess($query, $password)
    {
        return $query->where('user_access_password', $password)->first();
    }
}
