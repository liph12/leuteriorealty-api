<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\APIController;
use Illuminate\Http\Request;
use App\Services\AddressService;
use App\Http\Resources\API\BarangayResourceCollection;

class AddressController extends APIController
{
    protected $addressService;
    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function getBarangays(Request $request)
    {
        $barangays = $this->addressService->getBarangays($request->cityId);
        return new BarangayResourceCollection($barangays);
    }
}
