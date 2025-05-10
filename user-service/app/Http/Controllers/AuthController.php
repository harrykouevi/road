<?php

namespace App\Http\Controllers;

use App\Http\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
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
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository ;
    }

    public function register(Request $request)
    {
        try{ 
            $this->validate($request, User::$rules);

            // Création Firebase
            // $firebaseUser = app('firebase.auth')->createUser([
            //     'email' => $validated['email'],
            //     'password' => $validated['password'],
            //     'displayName' => $validated['name'],
            // ]);

            // Création dans la BDD locale
            $user = $this->userRepository->create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                // 'firebase_uid' => $firebaseUser->uid,
                'firebase_uid' => $request->input('app_uid'),
                'password' => bcrypt($request->input('password')),
                // 'password' => bcrypt('password126'),
            ]) ;
        
            return $this->sendResponse([
                // 'user' => $user->only(['name', 'email', 'avatar_url']),
                'user' => $user,
                'avatar_url' => $user->getFirstMediaUrl('avatars'),
            ], 'User retrieved successfully');
        } catch (ValidationException $e) {
            return $this->sendError(array_values($e->errors()),422);

        }
    }

    public function login(Request $request)
    {
        try{ 
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required',
                'firebase_token' => 'nullable|string'
            ]);

        
            if ($request->firebase_token) {
                $verifiedIdToken = app('firebase.auth')->verifyIdToken($validated['firebase_token']);
                $firebaseUid = $verifiedIdToken->claims()->get('sub');

                $user = User::where('firebase_uid', $firebaseUid)->first();
                if (!$user) {
                    return response()->json(['message' => 'Utilisateur introuvable.'], 404);
                }

                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json(['token' => $token, 'user' => $user]);
            } else {
                
                if (!Auth::attempt($request->only('email', 'password'))) {
                    return $this->sendError('Identifiants invalides.',401);
                }

                $user = Auth::user();
                $token = $user->createToken('auth_token')->plainTextToken;
                
                return $this->sendResponse([
                    'user' => $user->only(['name', 'email', 'avatar_url']),
                    'token' => $token,
                ], 'User retrieved successfully');
            }
        } catch (ValidationException $e) {
            return $this->sendError($e->errors(),422);

        }
    }

    public function check(Request $request): JsonResponse
    {
        return response()->json(['authenticated' => true, 'user' => Auth::user()]);
        
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnexion réussie']);
    }

  
}
