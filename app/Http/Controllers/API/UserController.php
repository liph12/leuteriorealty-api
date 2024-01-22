<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\UserService;
use App\Http\Controllers\APIController;
use App\Http\Resources\API\UserResource;

class UserController extends APIController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    
    public function authRequestLogin(Request $request)
    {
        $authenticated = $this->userService->authUser($request->email, $request->password);

        if($authenticated)
        {
            $user = $authenticated['user'];
            $authToken = $authenticated['authToken'];
            $authUserData = [new UserResource($user), 'authToken' => $authToken];

            return $this->successResponse($authUserData);
        }

        return $this->failResponse(Response::HTTP_FORBIDDEN, 'Invalid credentials.');
    }

    public function authRequestLogout()
    {
        auth()->user()->tokens()->delete();

        return $this->successResponse();
    }
    
    public function authResponse(Request $request)
    {
        return response()->json([
            'status'=>200,
            'message'=>'User authenticated.',
            'email' => $request->user()->email,
            'user_data' => $request->user()
        ],200);
    }
}
