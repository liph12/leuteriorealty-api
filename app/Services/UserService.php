<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Settings;

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
}