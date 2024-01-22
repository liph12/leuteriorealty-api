<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Response;

class APIController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function successResponse($data = null)
    {
        return response()->json([$data, 'success' => true], Response::HTTP_OK);
    }

    public function failResponse($res, $msg)
    {
        return response()->json(['message' => $msg, 'success' => false], $res);
    }
}
