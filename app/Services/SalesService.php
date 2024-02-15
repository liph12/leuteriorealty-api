<?php

namespace App\Services;

use App\Models\SalesReport;

class SalesService
{
   public function getSales($request)
   {
      return SalesReport::sales($request->id)->validStatus()->paginateSales();
   }

   public function getTotalSales($request)
   {
      $sales = SalesReport::sales($request->id);
      $valid = $sales->validSales();
      $invalid = $sales->invalidSales();
      $all = $sales->totalSales();

      $salesData = [
         'valid' => $valid,
         'invalid' => $invalid,
         'all' => $all
      ];

      return $salesData;
   }
}
