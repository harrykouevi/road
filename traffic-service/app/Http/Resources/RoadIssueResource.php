<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoadIssueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'report_type_id' => $this->report_type_id,
            'report_type' => [
                'id' => $this->reporttype->id,
                'name' => $this->reporttype->name,
                'color'  =>  $this->reporttype->color,
                'description' => $this->reporttype->description,
            ],
            // Pas de besoin d'envoyer l'image en base64, tu peux envoyer une URL
            'image' => url('storage/' . $this->image),
        ];
    }
}
