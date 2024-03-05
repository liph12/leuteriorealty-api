<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\APIController;
use Illuminate\Http\Request;
use App\Http\Resources\API\SalesResourceCollection;
use Illuminate\Support\Facades\Storage;
use App\Services\SalesService;
use Illuminate\Http\Response;
use App\Models\SalesReport;
use Carbon\Carbon;

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

    public function specificSale(Request $request)
    {
        $salesId = $request->id;

        $sales = SalesReport::where('id', '=', $salesId)->first();

        return $sales;
    }

    public function store_sales(Request $request)
    {
        try {
            // Get the current date
            $currentDate = Carbon::now();

            // Retrieve form inputs from the request09231640439
            $agentId = $request->input('agentid');
            $completename = $request->input('completename');
            $clientFirstname = $request->input('clientfirstname');
            $clientMiddlename = $request->input('clientmiddlename');
            $clientLastname = $request->input('clientlastname');
            $clientname = $clientFirstname . ' ' . $clientMiddlename . ' ' . $clientLastname;
            $clientEmail = $request->input('clientemail');
            $clientAge = $request->input('clientage');
            $clientAddress = $request->input('clientaddress');
            $clientGender = $request->input('clientgender');
            $clientMobile = $request->input('clientmobile');
            $clientCountry = $request->input('clientcountry');
            $unitNoBlock = $request->input('unitnoblock');
            $developer = $request->input('developer');
            $devid = $request->input('devid');
            $projectName = $request->input('projectname');
            $projid = $request->input('projid') === null ? null : $request->input('projid');
            $projid = $request->input('projid') === '' ? null : $request->input('projid');
            $propertyTypeId = $request->input('prop_type_id');
            $reservationDate = $request->input('reservationdate');
            $termOfPayment = $request->input('termofpayment');
            $status = $request->input('status');
            $quantity = $request->input('qty');
            $remarks = $request->input('remarks');
            $totalContractPrice = $request->input('tcprice');

            $unitNoBlock = $unitNoBlock !== '' ? $unitNoBlock : null;

            $propDetails = $request->input('prop_details') !== null ? $request->input('prop_details') : '[]';

            // Save form inputs to the database
            $salesReport = SalesReport::create([
                'agentid' => $agentId,
                'name' => $completename,
                'clientfamilyname' => $clientname,
                'clientAge' => $clientAge,
                'clientAddress' => $clientAddress,
                'clientGender' => $clientGender,
                'clientEmail' => $clientEmail,
                'clientMobile' => $clientMobile,
                'clientCountry' => $clientCountry,
                'developer' => $developer,
                'devid' => $devid,
                'projectname' => $projectName,
                'projid' => $projid,
                'prop_type_id' => $propertyTypeId,
                'qty' => $quantity,
                'tcprice' => $totalContractPrice,
                'reservationdate' => $reservationDate,
                'termofpayment' => $termOfPayment,
                'status' => $status,
                'remarks' => $remarks,
                'unitnum' => $unitNoBlock,
                'validSale' => '',
                'userupdate' => $agentId,
                'logs' => '',
                'unconfirm' => '0',
                'prop_details' => $propDetails,
                'dateadded' => $currentDate->format('m/d/Y'), // Month/Day/Year format
            ]);

            // Upload image to S3 bucket
            $file = $request->file('imageAssets');
            $monthYear = $currentDate->englishMonth . $currentDate->year;
            $day = $currentDate->day;
            $time = $currentDate->format('His');
            $filename = 'lr_app/proofoftrans/' . $monthYear . '/' . $day . '/' . $salesReport->id . '-' . $agentId . '-' . $time;
            Storage::disk('s3')->put($filename, file_get_contents($file), 'public');

            $currentDateFormatted = $currentDate->format('m/d/Y');

            $updateSales = SalesReport::where('id', '=', $salesReport->id)->update([
                'created_at' => $currentDateFormatted,
                'files' => 'https://filipinohomes123.s3.ap-southeast-1.amazonaws.com/' . $filename,
            ]);

            if ($request->sales_source == 'brokerage') {
                $totalContractPrice = ($totalContractPrice / 0.05);
                $reupdateSales = SalesReport::where('id', '=', $salesReport->id)->update([
                    'tcprice' => $totalContractPrice
                ]);
            } else if ($request->sales_source == 'rental') {
                $totalContractPrice = ($totalContractPrice / 0.083);
                $reupdateSales = SalesReport::where('id', '=', $salesReport->id)->update([
                    'tcprice' => $totalContractPrice
                ]);
            }

            // Return success response
            return response()->json(['message' => 'Sales added successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            // Return an error response if an exception occurs
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
