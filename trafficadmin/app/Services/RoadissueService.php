<?php

namespace App\Services;

use App\Models\FeaturedUser;
use App\Models\User;
use App\Models\UserHistory;
use App\Models\Image;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Builder ;
use InvalidArgumentException;

class RoadissueService
{
    use ServiceTrait ;
    
 
    public function fetchRoadIssues($filters = [])
    {
      
        $response = Http::withToken(session('token'))->withHeaders(['Accept' => 'application/json'])
        ->get(env('TRAFFIC_SERVICE_URL') . '/api/road-issues', $filters);
        
       
        if ($response->successful()) {
            $data = $response->json();
            // dd($response->json() , $filters) ;
            return collect($data);
        }
        // Gérer les erreurs ici
        throw new \Exception('Erreur lors de la récupération des incidents');
    }

  
    public function get($id,Array $relations=[])
    {
        $params = [];
        if (!empty($relation)) $params['with'] = implode(',', $relation);

        $response = Http::withToken(session('token'))->withHeaders(['Accept' => 'application/json'])
        ->get(env('TRAFFIC_SERVICE_URL') ."/api/road-issues/{$id}", $params);
        if ($response->successful()) {
            $data = $response->json()['data'];
            return collect($data);
        }
        throw new \Exception('Erreur lors de la récupération');
    }

    public function create(array $data)
    {
        $response = Http::withToken(session('token'))
            ->withHeaders(['Accept' => 'application/json'])
            ->post(env('TRAFFIC_SERVICE_URL') . "/api/road-issues", $data);
        return $response->json() ;
      
        // if ($response->successful()) {
        //     $data = $response->json()['data'];
        //     return collect($data);
        // } elseif ($response->status() === 422) {
        //     // Erreurs de validation Laravel
        //     $errors = $response->json()['errors'] ?? [];
        //     throw new \Exception('Erreur de validation : ' . json_encode($errors));
        // } else {
        //     // Autres erreurs
        //     throw new \Exception('Erreur lors de la création : ' . $response->body());
        // }
    }

    public function update($id, array $data)
    {
       
        $response = Http::withToken(session('token'))
            ->withHeaders(['Accept' => 'application/json'])
            ->put(env('TRAFFIC_SERVICE_URL') . "/api/road-issues/{$id}", $data);

        return $response->json() ;
        
        // if ($response->successful()) {
        //     $data = $response->json()['data'];
        //     return collect($data);
        // }
        // throw new \Exception('Erreur lors de la mise à jour');
    }

    public function delete($id)
    {
        
    }

}
