<?php

namespace App\Livewire;

use App\Services\RoadIssueTypeService;
use Livewire\Component;

class ListRoadIssueTypes extends Component
{
    public function render()
    {
        $incidentTypes = (new RoadIssueTypeService())->getAll() ;
        return view('livewire.list-road-issue-types', [
            'incidentTypes' =>  $incidentTypes,
        ]);

        
    }
}
