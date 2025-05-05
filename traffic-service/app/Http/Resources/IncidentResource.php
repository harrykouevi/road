<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncidentResource extends JsonResource
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
            'nom' => $this->description,
            'id_type' => $this->report_type_id,
            'emplacement' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'adresse' => $this->adresse ?? null, // Si tu as un champ "adresse"
            ],
            // Pas de besoin d'envoyer l'image en base64, tu peux envoyer une URL
            'image' => url('storage/' . $this->image),
        ];
    }
}
