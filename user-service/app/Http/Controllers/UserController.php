<?php

namespace App\Http\Controllers;

use App\Http\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

// use Illuminate\Validation\ValidationException;
// use InfyOm\Generator\Criteria\LimitOffsetCriteria;
// use Prettus\Validator\Exceptions\ValidatorException;

class UserController extends Controller
{
     /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct(PartenerShipService $partenerShipService ,UserRepository $userRepository, UploadRepository $uploadRepository, RoleRepository $roleRepository, CustomFieldRepository $customFieldRepo)
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository ;

    }
    

    public function index()
    {
        $users =$this->userRepository->all() ;
        return $this->sendResponse(
            $users
        , 'Users retrieved successfully');
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $this->validate($request, User::$rules);
        if (isset($input['avatar']) && $input['avatar']) {
                    $cacheUpload = $this->uploadRepository->getByUuid($input['avatar']);
                    $mediaItem = $cacheUpload->getMedia('avatar')->first();
                    if ($user->hasMedia('avatar')) {
                        $user->getFirstMedia('avatar')->delete();
                    }
                    $mediaItem->copy($user, 'avatar');
                }
                $user = $this->userRepository->update($input, $id);

        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien média si existant
            $user->clearMediaCollection('avatars');

            // Ajouter le nouveau
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatars');
        }

        return $this->sendResponse([
            'user' => $request->user()->only(['name', 'email', 'avatar_url']),
            'avatar_url' => $user->getFirstMediaUrl('avatars'),
        ], 'User retrieved successfully');
    }

    /**
     * Display the specified Booking.
     * GET|HEAD /bookings/{id}
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function show(int $id, Request $request): JsonResponse
    {
        try {
            $this->userRepository->pushCriteria(new RequestCriteria($request));

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $user = $this->userRepository->findWithoutFail($id);
        if (empty($user)) {
            return $this->sendError('Booking not found');
        }
        return $this->sendResponse(collect($user), 'Booking retrieved successfully');
    }



    public function update($id, Request $request)
    {
        try {
            $this->userRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $olduser = $this->userRepository->findWithoutFail($id);
        if (empty($olduser)) {
            return $this->sendError('user not found');
        }
       
        try {
            $rules = User::$rules ;
            $rules['email'] = 'sometimes|email|unique:users,email,' . $id ;
            $this->validate($request, $rules);
            $input = $request->input() ;
            Log::info('Requête envoyée au microservice', [
                'url' => env('MICRO_SERVICE_AUTH_URL') . "/api/users/{$id}",
                'data' =>  $input,
                'token' => session('token'),
            ]);
            $user = $this->userRepository->update($input, $id);

            return $this->sendResponse([
                // 'user' => $request->user()->only(['name', 'email', 'avatar_url']),
                'user' =>  $user,
                'avatar_url' => $user->getFirstMediaUrl('avatars'),
            ], 'User retrieved successfully');

        } catch (ValidationException $e) {
            return $this->sendError($e->getMessage());
        }
    }


}
