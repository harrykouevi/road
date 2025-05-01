<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct(PartenerShipService $partenerShipService ,UserRepository $userRepository, UploadRepository $uploadRepository, RoleRepository $roleRepository, CustomFieldRepository $customFieldRepo)
    public function __construct()
    {
        parent::__construct();
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


}
