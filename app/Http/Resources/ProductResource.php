<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            'expiration_dates' => ExpirationDatesResource::collection($this->whenLoaded('expirationDates')),
            'description' => $this->description,
            'image' => $this->image,
            'nutriscore' => $this->nutriscore,
            'novagroup' => $this->novagroup,
            'ecoscore' => $this->ecoscore,
            'finished_at' => $this->finished_at,
            'added_to_purchase_list_at' => $this->added_to_purchase_list_at,
            'closest_expiration_date' => $this->closest_expiration_date,
            'expiration_date_count' => $this->expiration_date_count,
        ];
    }
}
