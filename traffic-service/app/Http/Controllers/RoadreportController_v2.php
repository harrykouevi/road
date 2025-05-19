<?php

namespace App\Http\Controllers;

use App\Criteria\Addresses\RoadreportCriteria;
use App\Events\ImageProcessed;
use App\Http\Repositories\RoadReportRepository;
use App\Http\Repositories\RoadReportTypeRepository;
use App\Http\Requests\StoreIncidentRequest;
use App\Http\Resources\IncidentResource;
use App\Models\RoadReport ;
use Illuminate\Validation\ValidationException;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoadreportController_v2 extends Controller
{
/**
     * @var RoadReportTypeRepository
     */
    private RoadReportTypeRepository $roadReportTypeRepository;

    /**
     * @var RoadReportRepository
     */
    private RoadReportRepository $roadreportRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RoadReportTypeRepository $roadReportTypeRepository ,RoadReportRepository $roadreportRepository)
    {
        parent::__construct();
        $this->roadReportTypeRepository = $roadReportTypeRepository ;
        $this->roadreportRepository = $roadreportRepository ;
    }

   

    public function index(Request $request): JsonResponse
    {
        try {
            $this->roadreportRepository->pushCriteria(new RequestCriteria($request));
            $this->roadreportRepository->scopeQuery(RoadreportCriteria::applyQuery($request));
            if($request->has('per_page') ){
                $perPage = min((int) $request->get('per_page', 10), 100);
                $roadrport = $this->roadreportRepository->paginate($perPage);
                // $roadrport = RoadIssueResource::collection($roadrport);
            }else{
                $roadrport = $this->roadreportRepository->all();
            }
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        // $roadrport = $this->roadreportRepository->all();
        $roadrport =IncidentResource::collection($roadrport);
        return $this->sendResponse($roadrport, 'Report retrieved successfully');
    }

    /**
     * Display the specified Repport.
     * GET|HEAD /road-issues/{id}
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function show(int $id, Request $request): JsonResponse
    {
        try {
            $this->roadreportRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $roadrport = $this->roadreportRepository->findWithoutFail($id);
        if (is_null($roadrport)) {
            return $this->sendError('Repport not found');
        }
        return $this->sendResponse(new IncidentResource($roadrport), 'Repport retrieved successfully');

    }

    /**
     * Push the specified Repport in storage.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(StoreIncidentRequest $request) : JsonResponse
    {
        $user = $request->get('auth_user');
        $user_id = $request->get('user_id');
        
        $input = $request->all();
        // Stocker l'image
        // $imagePath = $request->file('image')->store('incidents', 'public');
        // Création dans la BDD locale

        $in = [
            'description' => $input['nom'],
            'report_type_id' => $input['id_type'],
            'latitude' => $input['emplacement']['latitude'],
            'longitude' => $input['emplacement']['longitude'],
            'addresse' => $input['emplacement']['adresse'] ?? null,
            'user' => $user,
            'user_id' => !is_null($user)? $user['id'] : $user_id, // ou autre logique
            //  'image' => $imagePath,
        ];
        // Sauvegarde
        $roadrport = $this->roadreportRepository->create($in) ;
        // Déclenchement en arrière-plan
        if(array_key_exists('image',$input) ) event(new ImageProcessed($input['image'],  $roadrport->id));

    
        return $this->sendResponse(new IncidentResource($roadrport), 'Repport added successfully');
       
    }

    /**
     * Update the specified Repport in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function update(int $id, StoreIncidentRequest $request): JsonResponse
    {
        $oldRepport = $this->roadreportRepository->findWithoutFail($id);
        if (empty($oldRepport)) {
            return $this->sendError('Repport not found');
        }
       
            $input = $request->all();
            $in = [
                'description' => $input['nom'],
                'report_type_id' => $input['id_type'],
                'latitude' => $input['emplacement']['latitude'],
                'longitude' => $input['emplacement']['longitude'],
                'addresse' => $input['emplacement']['adresse'] ?? null,
               
              //  'image' => $imagePath,
            ];
            $roadrport = $this->roadreportRepository->update($in, $id);
            if(array_key_exists('image',$input) ) event(new ImageProcessed($input['image'],  $roadrport->id));

            // event(new RepportChangedEvent($roadrport));
            return $this->sendResponse(new IncidentResource($roadrport), 'Repport updated successfully');
           
        
    }


    
}
