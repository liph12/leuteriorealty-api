<?php

namespace App\Services;

use App\Models\SalesTeam;
use App\Models\SalesTeamMember;
use App\Models\SalesSubTeam;
use App\Models\SalesSubTeamMember;
use App\Models\Member;

class SalesTeamService
{
   public function storeNewMember($upline, $member)
   {
      $exp_year = intval(date('Y')) + 6;
      $upline = Member::upline($upline->id);

      if (isset($upline->sales_team_member->teamid)) {
          $team_member = SalesTeamMember::create([
              'teamid' => $upline->sales_team_member->teamid,
              'memid' => $member->id,
              'datejoined' => date('Y-m-d'),
              'dateresigned' => $exp_year . '-12-31',
              'isleader' => 0,
              'activeTeam' => 1,
              'agentid' => $member->memberid
          ]);

          if (isset($upline->sales_team_subteam_member->subTeamID)) {
              $sub_team_member = SalesSubTeamMember::create([
                  'subTeamID' => $upline->sales_team_subteam_member->subTeamID,
                  'memID' => $team_member->memid,
                  'dateresigned' => $exp_year . '-12-31',
                  'agentid' => $team_member->agentid,
                  'isLeader' => 0,
              ]);
          }
      }
   }
}