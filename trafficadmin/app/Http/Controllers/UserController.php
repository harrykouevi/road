<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function index()
    {
       
         return view('users.list'); 
    }

    public function show($id)
    {
        // Recherche l'utilisateur par ID
        // $user = (new UserService())->get($id) ;
        // Retourne la vue avec les données de l'utilisateur
        // return view('users.edit', compact('user'));
        return view('users.edit', compact('id'));
    }
}
