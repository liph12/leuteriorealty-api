<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Developer;
use App\Models\Categories;

class DeveloperController extends Controller
{
    public function get_developer_list()
    {
        $developer_list = Developer::select('id', 'name')
            ->where('isbroker', '=', '0')
            ->orderBy('name') // Sort by name alphabetically
            ->get();

        return $developer_list;
    }

    public function get_brokerage_list()
    {
        $brokerage_list = Developer::select('id', 'name')
            ->where('isbroker', '=', '1')
            ->orderBy('name') // Sort by name alphabetically
            ->get();

        return $brokerage_list;
    }

    public function get_categories()
    {
        $category_list = Categories::get();

        return $category_list;
    }
}
