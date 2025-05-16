<?php

namespace App\Livewire;

use App\Services\RoadissueService;
use App\Services\RoadIssueTypeService;
use Livewire\Component;

class RoadIssueForm extends Component
{
    public $roadissue;
    public $roadissueId;
    public $description;
    public $report_type_id;
    public $latitude ;
    public $longitude ;
    public $incidentTypes ;


    public function mount($id = null)
    {
        if ($id) {
            $this->roadissue = $roadissue = (new RoadissueService())->get($id) ;
            $this->roadissueId = $roadissue['id'];
            $this->description = $roadissue['description'];
            $this->report_type_id = $roadissue['report_type_id'];
            $this->latitude = $roadissue['latitude'];
            $this->longitude = $roadissue['longitude'];
            // $this->email = $roadissue['email'];
        }
        $this->incidentTypes = (new RoadIssueTypeService())->getAll() ;
    }

    public function save()
    {
        $data = $this->validate([
            'description' => 'required|string|max:255',
            'report_type_id' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
        ]);


        if ($this->roadissueId) {
            (new RoadissueService())->update($this->roadissueId,$data) ; 
            session()->flash('message', 'Incident mise à jour.');
        } else {
             (new RoadissueService())->create($data) ; 
            session()->flash('message', 'Incident créé.');
        }

        return redirect()->route('roadissues.index');
    }

    

    
    public function render()
    {
        return view('livewire.road-issue-form');
    }
}
