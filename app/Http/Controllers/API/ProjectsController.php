<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Projects;

class ProjectsController extends Controller
{
    public function get_developer_projects_list(Request $request)
    {
        $project_list = Projects::select('id', 'name', 'prop_type_id')
            ->where('devid', '=', $request->dev_id)
            ->orderBy('name') // Sort by name alphabetically
            ->get();

        return $project_list;
    }
}
