<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SalesTeamMember;
use App\Models\SalesSubTeamMember;

class Member extends Model
{
    use HasFactory;
    const VERIFICATION_PROCESS = 1;
    const BASIC_INFO_PROCESS = 2;
    const ADDITIONAL_INFO_PROCESS = 3;
    const REGISTERED = 4;

    protected $fillable = [
        'inviterid',
        'prc',
        'hlurb',
        'fblink',
        'emailad',
        'email',
        'fn',
        'mn',
        'ln',
        'memberid',
        'gender',
        'birthday',
        'citizenship',
        'maritalstatus',
        'phone',
        'mobile',
        'fax',
        'tin',
        'address',
        'addresstwo',
        'state',
        'country',
        'city',
        'latitude',
        'longitude',
        'zipcode',
        'institution',
        'degree',
        'workexperience',
        'aboutyourself',
        'referencecontact',
        'specialskills',
        'datesign',
        'bdoaccountname',
        'bdoaccount',
        'national_intern',
        'registration_status',
        'remarks'
    ];

    public function scopeFindByEmail($query, $email)
    {
        return $query->where('email', $email)->first();
    }

    public function sales_team_member()
    {
        return $this->belongsTo(SalesTeamMember::class, "memberid", "agentid");
    }

    public function sales_team_subteam_member()
    {
        return $this->belongsTo(SalesSubTeamMember::class, 'memberid', 'agentid');
    }

    public function scopeUpline($query, $id)
    {
        return $query->find($id)->with(['sales_team_member','sales_team_subteam_member'])->first();
    }
}