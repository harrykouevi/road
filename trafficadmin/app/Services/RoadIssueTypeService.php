<?php

namespace App\Services;

use App\Models\FeaturedUser;
use App\Models\User;
use App\Models\UserHistory;
use App\Models\Image;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Builder ;
use InvalidArgumentException;

class RoadIssueTypeService
{
    use ServiceTrait ;
    
    public function getAll(Array $relation = [], int $perPage = Null)
    {
      
        $params = [];

        if (!empty($relation)) $params['with'] = implode(',', $relation);
        if ($perPage) $params['per_page'] = $perPage;

        $response = Http::withHeaders(['Accept' => 'application/json'])
        ->get(env('TRAFFIC_SERVICE_URL') .'/api/road-issue-types', $params);

        if ($response->successful()) {
            $data = $response->json()['data'];
            return collect($data);
        }

        // Gérer les erreurs ici
        throw new \Exception('Erreur lors de la récupération des types d\'utilisateur');
    }
    

    public function search(String $search , Array $relation = [], int $perPage = Null)  {
        // Query annonces with pagination, optionally filtering by search term
        $annonce_builder = User::where('titre', 'like', '%' . $search . '%')
                ->where('titre', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%")
                ->orWhere('adresse', 'LIKE', "%{$search}%")
                ->orWhere('prix', 'LIKE', "%{$search}%")
                ->orWhere('surface', 'LIKE', "%{$search}%")
                ->orWhere('wcdouche', 'LIKE', "%{$search}%")
                ->orWhere('nbpieces', 'LIKE', "%{$search}%")
                ->orWhere('nbsalon', 'LIKE', "%{$search}%")
                ->orWhereHas('Category', function ($query) use ($search) {
                    $query->where('nom', 'LIKE', "%{$search}%");
                }) ;

        if(!empty($relation)){
            $annonce_builder = $this->getRelation( $annonce_builder,$relation) ;
        }
        if($perPage){
            return  $annonce_builder->paginate($perPage);
        } else {
            return  $annonce_builder->get() ;
        }
    }

  
    public function get($id,Array $relations=[])
    {
        return User::with('images')->findOrFail($id);
        if(!empty($relations)){
            foreach($relations as $relation){
                if (!is_string($relation)) {
                    throw new InvalidArgumentException('All elements in relations must be strings.');
                }
            }
            $e = implode(", ", $relations) ;
            return User::with($e)->findOrFail($id);
        }else{
            return User::findOrFail($id);
        }

    }

    public function create(array $data)
    {
        // Créer l'annonce et associer l'utilisateur connecté
        $annonce = new User();
        $annonce->titre = $data['titre'];
        $annonce->description = $data['description'];
        $annonce->prix = $data['prix'];
        $annonce->nbpieces = $data['nbpieces'];
        $annonce->nbsalon = $data['nbsalon'];
        $annonce->wcdouche = $data['wcdouche'];
        $annonce->adresse = $data['adresse'];
        $annonce->phonenumber = $data['phonenumber'];
        $annonce->content_ = $data['content_'];
        $annonce->contacts_ = $data['contacts_'];
        $annonce->category_id = $data['category_id'];
        $annonce->user_id = auth()->id(); // ID de l'utilisateur connecté
        $annonce->save();
        return $annonce ;
    }

    public function update($id, array $data)
    {
        $annonce = $this->get($id);
        $annonce->update($data);
        return $annonce;
    }

    public function delete($id)
    {
        $annonce = $this->get($id);
        $annonce->delete();
    }



    /**
     * Retirer un article à la une.
     *
     * @param int $annonceId
     */
    // public function removeFromFeatured($annonceId)
    // {
    //     $annonce = $this->get($annonceId);
    //     if(!is_null($annonce)){
    //     FeaturedUser::where('annonce_id', $annonceId)
    //     ->delete();}
    //     return true;
    // }


    // public function imgCreate(array $data)
    // {
    //     // Créer l'image et associer l'utilisateur connecté
    //     $image = new Image();
    //     $image->path = $data['path'];
    //     $image->annonce_id = $data['annonce_id'];
    //     $image->save();
    //     return $image ;
    // }

}
