<?php

namespace App\Http\Controllers;

use App\Http\Repositories\RoadReportRepository;
use App\Http\Repositories\RoadReportTypeRepository;
use App\Models\RoadReport ;
use Illuminate\Validation\ValidationException;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        try {
            $this->roadreportRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $roadtypes = $this->roadreportRepository->all();
        return $this->sendResponse($roadtypes->toArray(), 'Report retrieved successfully');
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
