<?php

namespace App\Http\Controllers;

use App\Criteria\Addresses\RoadreportCriteria;
use App\Events\ImageProcessed;
use App\Http\Repositories\RoadReportRepository;
use App\Http\Repositories\RoadReportTypeRepository;
use App\Http\Resources\RoadIssueCollection;
use App\Http\Resources\RoadIssueResource;
use App\Models\RoadReport ;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoadreportController extends Controller
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

    public function getTypeOfRepport(Request $request): JsonResponse
    {
        try {
            $this->roadReportTypeRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $roadtypes = $this->roadReportTypeRepository->all();
        return $this->sendResponse($roadtypes->toArray(), 'ReportTypes retrieved successfully');
    }

    public function index(Request $request): JsonResponse
    {
        
        $request->validate([
            'keyword' => ['nullable', 'string', 'max:100']
        ]);
        // DB::enableQueryLog();

      
        try {
            
            // $this->roadreportRepository->pushCriteria(new RequestCriteria($request));>all()
            $this->roadreportRepository->scopeQuery(RoadreportCriteria::applyQuery($request));

            if($request->has('per_page') ){
                $perPage = min((int) $request->get('per_page', 10), 100);
                $roadrport = $this->roadreportRepository->paginate($perPage);
                // $roadrport = RoadIssueResource::collection($roadrport);
            }else{
                $roadrport = $this->roadreportRepository->all();
            }
            
            return $this->sendResponse(RoadIssueResource::collection($roadrport), 'Report retrieved successfully'); 
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
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
        return $this->sendResponse(new RoadIssueResource($roadrport), 'Repport retrieved successfully');

    }

    /**
     * Push the specified Repport in storage.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request) : JsonResponse
    {
        $user = $request->get('auth_user');
        $user_id = $request->get('user_id');
        
        try{ 
            $this->validate($request, RoadReport::$rules);
            $input = $request->all();
            $input['user_id'] = !is_null($user)? $user['id'] : $user_id;
            $input['user'] = $user;
            // Création dans la BDD locale
            $roadrport = $this->roadreportRepository->create($input) ;

            // Déclenchement en arrière-plan de l'enregistrement d'image
            if(array_key_exists('image',$input) ) event(new ImageProcessed($input['image'],  $roadrport->id));
            event(new ImageProcessed($input['image'],  $roadrport->id));
        
            return $this->sendResponse(new RoadIssueResource($roadrport), 'Repport added successfully');
        } catch (ValidationException $e) {
            return $this->sendError(array_values($e->errors()),422);
        }
    }

    /**
     * Update the specified Repport in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $oldRepport = $this->roadreportRepository->findWithoutFail($id);
        if (empty($oldRepport)) {
            return $this->sendError('Repport not found');
        }
       
        try {
            $this->validate($request, RoadReport::$rules);
            $input = $request->all();
            $roadrport = $this->roadreportRepository->update($input, $id);
            // Déclenchement en arrière-plan de l'enregistrement d'image
            if(array_key_exists('image',$input) ) event(new ImageProcessed($input['image'],  $roadrport->id));
        
            return $this->sendResponse(new RoadIssueResource($roadrport), 'Repport updated successfully');
           
        } catch (ValidationException $e) {
            return $this->sendError($e->getMessage());
        }
    }


    /**
     * Remove the specified Repport from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $oldRepport = $this->roadreportRepository->findWithoutFail($id);
        if (empty($oldRepport)) {
            return $this->sendError('Repport not found');
        }
        try {  
            $this->roadreportRepository->delete($id);
            return $this->sendResponse($oldRepport->only(['id']), 'Repport deleted successfully');

        } catch (ValidationException $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
