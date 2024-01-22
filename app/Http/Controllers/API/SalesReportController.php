<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\APIController;
use Illuminate\Http\Request;
use App\Http\Resources\API\SalesResourceCollection;
use App\Services\SalesService;

class SalesReportController extends APIController
{
    protected $salesService;

    public function __construct(SalesService $ss)
    {
        $this->salesService = $ss;
    }

    public function personalSales(Request $request)
    {
        $sales = $this->salesService->getSales($request);
        return new SalesResourceCollection($sales);
    }
}
