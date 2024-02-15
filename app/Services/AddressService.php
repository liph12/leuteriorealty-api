<?php

namespace App\Services;

use App\Models\Barangay;

class AddressService
{
   public function getBarangays($id)
   {
      return Barangay::getMunicipalityBrgy($id)->get();
   }
}