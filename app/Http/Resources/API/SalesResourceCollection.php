<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SalesResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'salesreport' => $this->collection,
            'links' => [
                'self' => 'link-value',
            ]
        ];
    }
}
