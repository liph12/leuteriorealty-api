<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'memberId' => $this->agentid,
            'memberName' => $this->name,
            'clientName' => $this->clientfamilyname,
            'clientAge' => $this->clientAge,
            'clientAddress' => $this->clientAddress,
            'clientGender' => $this->clientGender,
            'clientEmail' => $this->clientEmail,
            'clientMobile' => $this->clientMobile,
            'clientCountry' => $this->clientCountry,
            'developer' => $this->developer,
            'developerId' => $this->devid,
            'project' => $this->projectname,
            'projId' => $this->projid,
            'project' => $this->projectname,
            'propertyTypeId' => $this->prop_type_id,
            'quantity' => $this->qty,
            'tcPrice' => $this->tcprice,
            'unitNumber' => $this->unitnum,
            'propertyDetails' => $this->prop_details,
            'reservationDate' => $this->reservationdate,
            'termOfPayment' => $this->termofpayment,
            'validSale' => $this->validSale,
            'status' => $this->status,
            'remarks' => $this->remarks,
            'partialclaimed' => $this->partialclaimed,
            'file' => $this->file,
        ];
    }
}
