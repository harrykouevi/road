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
    public function toArray(Request $request): array
    {
        
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'addresse' => $this->addresse,
            'description' => $this->description,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'report_type_id' => $this->report_type_id,
            'reporttype' => [
                'id' => $this->reporttype->id,
                'name' => $this->reporttype->name,
                'color'  =>  $this->reporttype->color,
                'description' => $this->reporttype->description,
            ],
            'user' => $this->user,
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i:s'),
            'validated_at' => !is_null($this->validated_at)? $this->validated_at : Null,

            // Pas de besoin d'envoyer l'image en base64, tu peux envoyer une URL
            'image' => !is_null( $this->image_path) ? url('storage/' . $this->image_path) : Null,

        ];
    }
}
