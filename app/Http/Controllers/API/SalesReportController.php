<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\APIController;
use Illuminate\Http\Request;
use App\Http\Resources\API\SalesResourceCollection;
use App\Services\SalesService;
use App\Models\SalesReport;

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

    public function totalSales(Request $request)
    {
        $sales = $this->salesService->getTotalSales($request);
        return $sales;
    }

    public function yearlySales(Request $request)
    {
        $agentId = $request->id;

        if (!$agentId) {
            return response()->json(['error' => 'Agent ID is required.'], 400);
        }

        $curr_year = date('Y');
        $curr_date_range = date('Y-m-d', strtotime('01/01/' . $curr_year));

        $sales = SalesReport::selectRaw("COUNT(*) as sales_count, agentid, reservationdate, SUM(tcprice) as totalsales")
            ->whereRaw("STR_TO_DATE(reservationdate, '%m/%d/%Y') >= '" . $curr_date_range . "'")
            ->where([
                ['deleted', '0'],
                ['status', '!=', 'Cancelled'],
                ['unconfirm', '0'],
                ['validSale', 'Yes'],
                ['agentid', $agentId]
            ])->groupBy('agentid', 'reservationdate')->get();

        $SALES_DATA = [
            "01" => 0,
            "02" => 0,
            "03" => 0,
            "04" => 0,
            "05" => 0,
            "06" => 0,
            "07" => 0,
            "08" => 0,
            "09" => 0,
            "10" => 0,
            "11" => 0,
            "12" => 0,
        ];

        foreach ($sales as $sale) {
            $mon = explode('/', $sale->reservationdate)[0];
            $SALES_DATA[$mon] += $sale->totalsales;
        }

        return $SALES_DATA;
    }

    public function summarySales(Request $request)
    {
        $agentId = $request->id;

        if (!$agentId) {
            return response()->json(['error' => 'Agent ID is required.'], 400);
        }

        $totalSales = SalesReport::where([
            ['agentid', $agentId]
        ])->sum('tcprice');

        $totalValidSales = SalesReport::where([
            ['deleted', '0'],
            ['status', '!=', 'Cancelled'],
            ['unconfirm', '0'],
            ['validSale', 'Yes'],
            ['agentid', $agentId]
        ])->sum('tcprice');

        $totalInvalidSales = SalesReport::where([
            ['deleted', '0'],
            ['status', '!=', 'Cancelled'],
            ['unconfirm', '0'],
            ['validSale', 'No'],
            ['agentid', $agentId]
        ])->sum('tcprice');

        $totalNotValidSales = SalesReport::where([
            ['deleted', '0'],
            ['status', '!=', 'Cancelled'],
            ['unconfirm', '0'],
            ['validSale', ''],
            ['agentid', $agentId]
        ])->sum('tcprice');

        return response()->json([
            'total_sales' => $totalSales,
            'total_valid_sales' => $totalValidSales,
            'total_invalid_sales' => $totalInvalidSales,
            'total_not_valid_sales' => $totalNotValidSales,
        ]);
    }
}
