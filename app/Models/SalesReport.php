<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReport extends Model
{
    use HasFactory;
    protected $table = "salesreport";
    protected $fillable = [
        'agentid',
        'name',
        'clientfamilyname',
        'clientAge',
        'clientAddress',
        'clientGender',
        'clientEmail',
        'clientMobile',
        'clientCountry',
        'developer',
        'devid',
        'projectname',
        'projid',
        'prop_type_id',
        'qty',
        'tcprice',
        'reservationdate',
        'termofpayment',
        'status',
        'remarks',
        'partialclaimed',
        'file',
        'broker_com',
        'userupdate',
        'unitnum',
        'unconfirm',
        'request_file',
        'prop_details',
    ];

    public function scopeValidStatus($query)
    {
        return $query->where([['validSale', '!=', 'No'], ['status', '!=', 'Cancelled']]);
    }

    public function scopeInvalidStatus($query)
    {
        return $query->where([['validSale', 'No'], ['status', '!=', 'Cancelled']]);
    }

    public function scopeSales($query, $id)
    {
        return $query->where('agentid', $id);
    }

    public function scopePaginateSales($query) 
    {
        return $query->orderBy('id', 'DESC')->paginate(3);
    }

    public function scopeTotalSales($query)
    {
        return $query->selectRaw('SUM(tcprice) as totalSales')->groupBy('agentid')->get();
    }

    public function scopeValidSales($query)
    {
        return $query->selectRaw('SUM(tcprice) as totalSales')->where('validSale', 'Yes')->where('validSale', '!=','Cancelled')->groupBy('agentid')->get();
    }

    public function scopeInvalidSales($query)
    {
        return $query->selectRaw('SUM(tcprice) as totalSales')->where('validSale', 'No')->where('validSale', '!=','Cancelled')->groupBy('agentid')->get();
    }
}
