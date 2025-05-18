<?php

namespace App\Livewire;

use Livewire\Component;

class IncidentValidation extends Component
{
     public $incident;
    public $comment = '';

    public function mount($incidentId)
    {
        $this->incident = RoadIssue::findOrFail($incidentId);
    }

    public function validateIncident()
    {
        $this->incident->status = 'validé';
        $this->incident->validated_at = now();
        $this->incident->save();

        session()->flash('message', 'Incident validé.');
        $this->redirect('/incidents');
    }

    public function refuseIncident()
    {
        $this->validate([
            'comment' => 'required|string|min:5',
        ]);

        $this->incident->status = 'refusé';
        $this->incident->comment = $this->comment;
        $this->incident->validated_at = now();
        $this->incident->save();

        session()->flash('message', 'Incident refusé.');
        $this->redirect('/incidents');
    }

    public function render()
    {
        return view('livewire.incident-validation');
    }
}
