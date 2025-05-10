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
}
