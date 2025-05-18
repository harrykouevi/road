<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Http;

class Controller
{


    public function index()
    {
        $total_issues = 0 ;
        $total_users=0 ;
        $last_issue = Null ;

        $response = Http::withHeaders(['Accept' => 'application/json'])
        ->get(env('TRAFFIC_SERVICE_URL') . '/api/stats');
        
       
        if ($response->successful()) {
            $data = $response->json();
            $total_issues = $data['data']['total_issues'] ;
            $last_issue = $data['data']['last_issue'] ;
        }

        // Appel du micro-service d'authentification
        $response = Http::withHeaders(['Accept' => 'application/json']) 
            ->get(env('MICRO_SERVICE_AUTH_URL') . '/api/stats');

        if ($response->successful()) {
            $data = $response->json();
            $total_users= $data['data']['total_users'] ;
        }
        return view('dashboard',['total_issues' =>  $total_issues ,
            'total_users' => $total_users ,
            'last_issue' => $last_issue 
        ]);

    }
}
