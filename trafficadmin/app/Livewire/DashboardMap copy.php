<?php

namespace App\Livewire;

use Livewire\Component;

class DashboardMap extends Component
{
    public string $mode = 'incident';
    public $iframeUrl;
    public $urlparams = [] ;
    public ?string $endpoint = '';


    public ?string $filterType = null;
    public ?string $filterStatus = null;

    public ?float $incidentLatitude = null;
    public ?float $incidentLongitude = null;
    public ?float $incidentRadius = null;

    public ?float $departureLatitude = null;
    public ?float $departureLongitude = null;
    public ?float $arrivalLatitude = null;
    public ?float $arrivalLongitude = null;

    public function applyFilters()
    {
        if ($this->mode === 'incident') {
            // Appliquer les filtres incidents
            $this->urlparams = [
               
                'lat' => $this->incidentLatitude,
                'lng' => $this->incidentLongitude,
                'radius' => $this->incidentRadius,
            ];


            $this->endpoint = route('map-incidents') ;

            //lat=6.1751&lng=1.2123&radius=1000
        } elseif ($this->mode === 'itineraire') {
            // Appliquer la recherche d'itinéraire
        }
        $this->updateIframeUrl();
    }

    public function mount()
    {
        $this->endpoint = route('map-incidents')  ;
        $this->urlparams = [
               
            'lat' => $this->incidentLatitude = 6.1751,
            'lng' => $this->incidentLongitude = 1.2123,
            'radius' => $this->incidentRadius =1000,
        ];

        $this->updateIframeUrl();
    }

    public function updated($property)
    {
        $this->updateIframeUrl();
    }

    public function updateIframeUrl()
    {
        $params = http_build_query($this->urlparams);

        $this->iframeUrl = $this->endpoint  . '?' . $params;
    }
    public function render()
    {
        return view('livewire.dashboard-map');

    }
}
