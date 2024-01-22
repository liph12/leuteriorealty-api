<?php

namespace App\Services;

use App\Models\SalesReport;

class SalesService
{
   public function getSales($request)
   {
      return SalesReport::sales($request->id)->validStatus()->paginateSales();
   }
}