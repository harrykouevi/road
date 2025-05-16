<?php

namespace App\Http\Controllers;

use App\Http\Repositories\RoadReportRepository;
use App\Http\Repositories\RoadReportTypeRepository;
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
            $this->roadreportRepository->scopeQuery(function ($query) use ($request) {
                
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
                        $query->where('description', 'like', '%' . $request->input('keyword') . '%') ;
                            // ->orWhere('nom', 'like', '%' . $request->input('keyword') . '%');
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
            });

           
            // dd('ggtt') ;
            //forcer la pagination avec  limite la taille max :
            $perPage = min((int) $request->get('per_page', 10), 100);
            if($request->has('per_page') || $perPage ){
                $roadrport = $this->roadreportRepository->paginate($perPage);
            }else{
                $roadrport = $this->roadreportRepository->all();
            }
            
            return $this->sendResponse(collect($roadrport), 'Report retrieved successfully'); 
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
        $repport = $this->roadreportRepository->findWithoutFail($id);
        if (is_null($repport)) {
            return $this->sendError('Repport not found');
        }
        return $this->sendResponse($repport->toArray(), 'Repport retrieved successfully');

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
            // Création dans la BDD locale
            $report = $this->roadreportRepository->create($input) ;
        
            return $this->sendResponse($report->toArray(), 'Repport added successfully');
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
            $repport = $this->roadreportRepository->update($input, $id);
            // event(new RepportChangedEvent($repport));
            return $this->sendResponse($repport->toArray(), 'Repport updated successfully');
           
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
