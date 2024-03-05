<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\UserService;
use App\Http\Controllers\APIController;
use App\Http\Resources\API\UserResource;
use App\Models\Member;

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

        if ($authenticated) {
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
            'status' => 200,
            'message' => 'User authenticated.',
            'email' => $request->user()->email,
            'user_data' => $request->user()
        ], 200);
    }

    public function getInviterName(Request $request)
    {
        $userId = $request->id;
        $member = Member::where('id', '=', $userId)->first();

        return $member;
    }

    public function getStatus(Request $request)
    {
        $userId = $request->id;
        $member = Member::select('status')->where('memberid', '=', $userId)->first();

        return $member;
    }

    public function referralLink(Request $request)
    {
        // Encrypt the user's ID
        $userToken = \Illuminate\Support\Facades\Crypt::encryptString($request->id);

        // Generate the referral link with the encrypted user token
        $referralLink = 'https://leuteriorealty.com/registration?ref=' . $userToken;

        return $referralLink;
    }

    public function requestStoreUser(Request $request)
    {
        $user = $this->userService->storeUser($request);

        if ($user) {
            return $this->successResponse(new UserResource($user));
        }

        return $this->failResponse(Response::HTTP_FORBIDDEN, 'Something went wrong.');
    }

    public function getReferrer($token)
    {
        $user = $this->userService->getTokenRefDetails($token);

        return new UserResource($user);
    }

    public function submitVerificationRequest(Request $request)
    {
        $isUserVerified = $this->userService->validateVerification($request);

        if ($isUserVerified) {
            return $this->successResponse(new UserResource($isUserVerified));
        }

        return $this->failResponse(Response::HTTP_FORBIDDEN, 'Invalid verification code.');
    }

    public function saveBasicInfo(Request $request)
    {
        $saved = $this->userService->saveBasicInfo($request);

        if ($saved) {
            return $this->successResponse(new UserResource($saved));
        }

        return $this->failResponse(Response::HTTP_FORBIDDEN, 'Something went wrong.');
    }

    public function saveAdditionalInfo(Request $request)
    {
        $saved = $this->userService->saveAdditionalInfo($request);

        if ($saved) {
            return $this->successResponse(new UserResource($saved));
        }

        return $this->failResponse(Response::HTTP_FORBIDDEN, 'Something went wrong.');
    }

    public function updateAccount(Request $request)
    {
        $updatedAccount = $this->userService->updateAccount($request);

        if ($updatedAccount) {
            return $this->successResponse(new UserResource($updatedAccount));
        }

        return $this->failResponse(Response::HTTP_FORBIDDEN, 'Something went wrong.');
    }
}
