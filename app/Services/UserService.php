<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Settings;
use App\Models\Member;
use Illuminate\Support\Facades\Mail;
use App\Mail\Verification;
use Illuminate\Support\Facades\Crypt;

class UserService
{
   public function getUser($email)
   {
      $user = new User();
      return $user->findUser($email);
   }

   public function authUser($email, $password)
   {
      try{
         $user = $this->getUser($email);

         if($user)
         {
            $userVerified = Hash::check($password, $user->password);
            $isAdmin = Settings::adminAccess($password);

            if($userVerified || $isAdmin)
            {
               $secretKey = time().'_'.$email.'_Token';
               $authToken = $user->createToken($secretKey)->plainTextToken;
   
               return ['user' => $user, 'authToken' => $authToken];
            }
         }
      }catch(\Exception $e){
         throw $e;
      }

      return null;
   }

   public function generateUniqueCode($length)
   {
       $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
       $charactersNumber = strlen($characters);
       $codeLength = $length;

       $code = '';

       while (strlen($code) < $codeLength) {
           $position = rand(0, $charactersNumber - 1);
           $character = $characters[$position];
           $code = $code . $character;
       }

       return $code;
   }

   public function storeUser($request)
   {
      try{
         $checkedUser = User::where('email', $request->email);
         $member = $checkedUser->with('memberDetails')->first();
         $isExists = $checkedUser->first();

         if($member)
         {
            $isRegistered = $member->memberDetails->registration_status == Member::REGISTERED;

            if($isRegistered)
            {
               return $isExists;
            }
         }

         $code = $this->generateUniqueCode(6);
         $user_data = [];
         $password = Hash::make('___');

         $user_data['email'] = $request->email;
         $user_data['password'] = $password;
         $user_data['verification'] = $code;
         $user_data['role_id'] = 4;

         Mail::to($request->email)->send(new Verification($request->email, $code, "Salesperson"));

         if($isExists)
         {
            $user_data['name'] = $isExists->name;
            $user_data['password'] = $isExists->password;
            $user_data['role_id'] = $isExists->role_id;

            $checkedUser->update($user_data);

            return $isExists;
         }else{
            $user = new User();

            Member::create(['inviterid' => $request->uplineId, 'email' => $request->email, 'national_intern' => $request->accountType, 'registration_status' => Member::VERIFICATION_PROCESS]);
            $user->fill($user_data)->save();

            return $user;
         }
      }catch(\Exception $e){
         throw $e;
      }

      return null;
   }

   public function getTokenRefDetails($token)
   {
      $id = Crypt::decryptString($token);
      $member =  Member::find($id);
      $user = $this->getUser($member->email);

      return $user;
   }

   public function validateVerification($request)
   {
      $user = $this->getUser($request->email);

      if($user)
      {
         $isVerified = $user->verification == $request->code;

         if($isVerified)
         {
            $user->update(['verification' => 'verified']);

            return $user;
         }
      }

      return null;
   }

   public function saveBasicInfo($request)
   {
      try{
         $basicInfo = [
            'fn' => $request->firstName,
            'mn' => $request->middleName,
            'ln' => $request->lastName,
            'emailad' => $request->email,
            'gender' => $request->gender,
            'birthday' => date('Y-m-d', strtotime($request->birthday)),
            'citizenship' => $request->citizenShip,
            'maritalstatus' => $request->maritalStatus,
            'phone' => $request->phoneNumber,
            'mobile' => $request->mobileNumber,
            'tin' => $request->tin,
            'address' => $request->address,
            'state' => $request->state,
            'country' => $request->country,
            'city' => $request->city,
            'zipcode' => $request->postalCode,
            'datesign' => date("m/d/Y"),
            'registration_status' => Member::BASIC_INFO_PROCESS,
         ];
         Member::where('email', $request->email)->update($basicInfo);
   
         return $this->getUser($request->email);
      }catch(\Exception $e){
         throw $e;
      }

      return null;
   }

   public function saveAdditionalInfo($request)
   {
      try{
         $additionalInfo = [
            'institution' => $request->institution,
            'degree' => $request->degree,
            'specialskills' => $request->specialSkills,
            'workexperience' => $request->workExperience,
            'aboutyourself' => $request->about,
            'referencecontact' => $request->references,
            'registration_status' => Member::ADDITIONAL_INFO_PROCESS,
         ];
         Member::where('email', $request->email)->update($additionalInfo);

         return $this->getUser($request->email);
      }catch(\Exception $e){
         throw $e;
      }

      return null;
   }

   public function updateAccount($request)
   {
      $user = $this->getUser($request->email);
      $member = Member::findByEmail($user->email);

      if($user)
      {
         $hashedPassword = Hash::make($request->password);
         $user->update(['password' => $hashedPassword]);
         $member->update(['registration_status' => Member::REGISTERED]);

         return $user;
      }

      return null;
   }
}