<?php

namespace App\Livewire;

use App\Services\RoadissueService;
use Livewire\Component;
use Carbon\Carbon;

class RoadIssueManageView extends Component
{
    public $roadissueId;
    public $roadissue;
    public $comment = '';
    public string $successMessage = '';
    public function mount($roadissueId)
    {
        $this->roadissueId = $roadissueId;
        $this->roadissue = (new RoadissueService())->get($roadissueId) ;
    }
    

    public function validateRoadissue()
    {
        $this->resetErrorBag();
        try{ 
            $data = [
                'status' => 'validé',
                'validated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];
            
            $response = (new RoadissueService())->update($this->roadissueId,$data) ; 
            
            if ($response['success']== true) {
                $this->successMessage = '✅ Incident Validé !';
                // return redirect()->route('roadissues.index');
            } elseif ($response['success']== false) {
    
                $err = $response['errors'];
                foreach ($err as $field => $messages) {
                    foreach ($messages as $message) {
                        $this->addError('_mess_', $message);
                    }
                }
                $this->addError('general', 'Probleme survenu.');
            } 
        }catch (\Exception $e) {
            $this->addError('general', 'Erreur : ' . $e->getMessage());
        }
    }

    public function refuseRoadissue()
    {
        $this->resetErrorBag();

        try{ 
            $data = $this->validate([
                'comment' => 'required|string|min:5',
            ],
            ['comment.required' => 'Un commentaire est requis.',
            'comment.min' => 'Le commentaire doit faire au moins :min caractères.']);
            $data['status'] = 'refusé';
            $data['addresse'] = $this->roadissue['addresse'];
            $data['validated_at'] = Carbon::now()->format('Y-m-d H:i:s');  
            
            $response = (new RoadissueService())->update($this->roadissueId,$data) ; 
           
            if ($response['success']== true) {
                $this->successMessage = '✅ Incident refusé.';

                // return redirect()->route('roadissues.index');
            } elseif ($response['success']== false) {
    
                $err = $response['errors'];
                foreach ($err as $field => $messages) {
                    foreach ($messages as $message) {
                        $this->addError('_mess_', $message);
                    }
                }
                $this->addError('general', 'Probleme survenu.');

            } 
        }catch (\Exception $e) {
            $this->addError('general', 'Erreur : ' . $e->getMessage());
        }

    }

    public function render()
    {
        return view('livewire.road-issue-manage-view');
    }
}
