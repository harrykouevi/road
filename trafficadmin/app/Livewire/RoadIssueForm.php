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
    public $addresse;
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
            $this->addresse = $roadissue['addresse'] ;
            $this->report_type_id = $roadissue['report_type_id'];
            $this->latitude = $roadissue['latitude'];
            $this->longitude = $roadissue['longitude'];
            // $this->email = $roadissue['email'];
        }
        $this->incidentTypes = (new RoadIssueTypeService())->getAll() ;
    }

    public function save()
    {
        
        

        try{ 
            $data = $this->validate([
                'addresse' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'report_type_id' => 'nullable',
                'latitude' => 'nullable',
                'longitude' => 'nullable',
            ]);

            if ($this->roadissueId) {
                $response = (new RoadissueService())->update($this->roadissueId,$data) ; 
            } else {
                $response = (new RoadissueService())->create($data) ; 
            }

            if ($response['success']== true) {

                session()->flash('message', 'Problème signalé avec succès.');
                return redirect()->route('roadissues.index');

            } elseif ($response['success']== false) {
    
                $err = $response['errors'];
                foreach ($err as $field => $messages) {
                    foreach ($messages as $message) {
                        $this->addError('_mess_', $message);
                    }
                }
                session()->flash('message', 'Probleme survenu.');
            } 
        }catch (\Exception $e) {
            $this->addError('general', 'Erreur : ' . $e->getMessage());
        }
            
    }

    

    
    public function render()
    {
        return view('livewire.road-issue-form');
    }
}
