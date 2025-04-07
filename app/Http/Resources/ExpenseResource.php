<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'user_id'    => $this->user_id,
            'date'       => $this->date,
            'comment'    => $this->comment,
            'total'      => $this->total_amount,
            'items'      => ExpenseItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
