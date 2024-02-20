<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SalesTeam;

class SalesTeamMember extends Model
{
    use HasFactory;
    protected $table = "sales_team_members";
    protected $fillable = [
        "teamid",
        "memid",
        "datejoined",
        "dateresigned",
        "isleader",
        "activeTeam",
        "agentid",
    ];

    public function sales_team()
    {
        return $this->belongsTo(SalesTeam::class, 'teamid');
    }
}
