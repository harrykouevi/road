<?php

namespace App\Http\Controllers;

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
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $roadtypes = $this->roadreportRepository->all();
        $roadtypes =IncidentResource::collection($roadtypes);
        return $this->sendResponse($roadtypes, 'Report retrieved successfully');
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
        $repport = $this->roadreportRepository->findWithoutFail($id);
        if (is_null($repport)) {
            return $this->sendError('Repport not found');
        }
        return $this->sendResponse(new IncidentResource($repport), 'Repport retrieved successfully');

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

        $input = $request->all();
        // Stocker l'image
        // $imagePath = $request->file('image')->store('incidents', 'public');
        // Création dans la BDD locale

        $in = [
            'description' => $input['nom'],
            'report_type_id' => $input['id_type'],
            'latitude' => $input['emplacement']['latitude'],
            'longitude' => $input['emplacement']['longitude'],
            'adresse' => $input['emplacement']['adresse'] ?? null,
            'user_id' => $user['id'], // ou autre logique
            //  'image' => $imagePath,
        ];
        // Sauvegarde
        $report = $this->roadreportRepository->create($in) ;
    
        return $this->sendResponse(new IncidentResource($report), 'Repport added successfully');
       
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
                'adresse' => $input['emplacement']['adresse'] ?? null,
               
              //  'image' => $imagePath,
            ];
            $report = $this->roadreportRepository->update($in, $id);
            // event(new RepportChangedEvent($report));
            return $this->sendResponse(new IncidentResource($report), 'Repport updated successfully');
           
        
    }


    
}
