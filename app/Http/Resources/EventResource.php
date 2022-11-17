<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'is_live' => $this->is_live,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'formatted_start_date' => Carbon::parse($this->start_date)->format('d/m/Y'),
            'formatted_end_date' => Carbon::parse($this->end_date)->format('d/m/Y')
        ];
    }
}
