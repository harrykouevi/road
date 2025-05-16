<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoadIssueController extends Controller
{
    //

    public function getissuetypes()
    {
        return view('issuetypes.list'); 
    }

    public function index()
    {
         return view('issues.list'); 
    }

    public function update($id)
    {
        return view('issues.edit', compact('id'));
    }

    public function create()
    {
        return view('issues.edit');
    }
}
