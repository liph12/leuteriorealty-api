<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SalesSubTeam;

class SalesSubTeamMember extends Model
{
    use HasFactory;
    protected $table = "salesteam_subteam_members";
    protected $fillable = [
        "subTeamID",
        "memID",
        "agentid",
        "isLeader",
        "dateresigned"
    ];

    public function sales_subteam()
    {
        return $this->belongsTo(SalesSubTeam::class, 'subTeamID');
    }
}
