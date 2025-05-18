<?php
/*
 * File name: AddressesOfUserCriteria.php
 * Last modified: 2024.04.18 at 18:19:46
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Criteria\Addresses;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class AddressesOfUser.
 *
 * @package namespace App\Criteria\Bookings;
 */
class RoadreportCriteria implements CriteriaInterface
{
    /**
     * @var ?int
     */
    private ?int $userId;

    /**
     * AddressesOfUser constructor.
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository): mixed
    {
       
        return $model->where('addresses.user_id', $this->userId);
    }

    static function applyQuery(Request $request){
        return   function ($query) use ($request) {
                
                $query = (new $query())->newQuery() ;
                $query->when($request->input('user_id'), fn($q) => $q->where('user_id', $request->input('user_id')));
                $query->when($request->input('report_type_id'), fn($q) => $q->where('report_type_id', $request->input('report_type_id')));
                // $query->when($request->input('status'), fn($q) => $q->where('status', $request->input('status')));
                $query->when($request->input('date'), fn($q) => $q->whereBetween('created_at', [
                    Carbon::parse($request->input('date'))->startOfDay(),
                    Carbon::parse($request->input('date'))->endOfDay()
                ]));
                
                if ($request->has('keyword')) {
                     $query->where(function ($query) use ($request) {
                        $query->where('description', 'like', '%' . $request->input('keyword') . '%') 
                            ->orWhere('addresse', 'like', '%' . $request->input('keyword') . '%');
                    }) ;
                }


                if ($request->filled('coordinate')) {
                    //GET /api/road-report-types?coordinate[lat]=4.2233&coordinate[lng]=4.2233&coordinate[radius]=4
              
                    $coord = $request->input('coordinate') ;
                    // Vérification de la présence et de la validité des 3 clés
                    if( isset($coord['lat'], $coord['lng'], $coord['radius']) &&
                        is_numeric($coord['lat']) &&
                        is_numeric($coord['lng']) &&
                        is_numeric($coord['radius'])
                    ) {
                        $lat = $coord['lat'];
                        $lng = $coord['lng'];
                        $radius = $coord['radius'];

                        $haversine = "(6371 * acos(
                            cos(radians($lat)) *
                            cos(radians(latitude)) *
                            cos(radians(longitude) - radians($lng)) +
                            sin(radians($lat)) *
                            sin(radians(latitude))
                        ))";

                        $query->selectRaw("*, $haversine AS distance")
                        ->having("distance", "<=", $radius)
                        ->orderBy("distance");
                    }
                }
                
                //Toujours finir par un tri indexé :
                $query->orderBy('created_at', 'desc');
                return $query;

                //GET /api/road-report-types?type=incident&status=active&date=2025-05-10&keyword=panne&coordinate[lat]=4.2233&coordinate[lng]=4.2233&coordinate[radius]=4
            } ;
    }
}

