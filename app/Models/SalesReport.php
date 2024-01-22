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

    public function scopeSales($query, $id)
    {
        return $query->where('agentid', $id);
    }

    public function scopePaginateSales($query)
    {
        return $query->orderBy('id', 'DESC')->paginate(5);
    }
}
