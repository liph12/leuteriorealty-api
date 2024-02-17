<?php

namespace App\Services;

use App\Models\SalesReport;

class SalesService
{
   public function getSales($request)
   {
      if($request->search == 'all')
      {
         return SalesReport::sales($request->id)->orderBy("id","DESC")->paginateSales();
      }
      else { 
         return SalesReport::sales($request->id)->where([
            ['clientfamilyname', 'LIKE', '%'.$request->search.'%']
        ])->validStatus()->orderBy("id","DESC")->paginateSales();
      }
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
