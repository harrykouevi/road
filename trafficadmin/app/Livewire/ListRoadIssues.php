<?php

namespace App\Livewire;

use App\Services\RoadissueService;
use Livewire\Component;
use Livewire\WithPagination;


class ListRoadIssues extends Component
{
    use WithPagination; // Include the WithPagination trait

    
    public $selectedIssue = null;
    public $iframeUrl;
    public $urlparams = [] ;
    public ?string $endpoint = '';



    public $filterType = '';
    public $filterStatus = '';
    public $filteKkeyword = '';
    public $filterDate = '';

    public $filterLatitude = '';
    public $filterLongitude = '';
    public $filterRadius = '';

    public $appliedFilters = [];

    public function mount()
    {
        // Au départ, pas de filtres appliqués
        $this->appliedFilters = [
            'per_page' => 20
        ];
    }

    public function applyFilters()
    {
        $this->appliedFilters = [] ;
        if($this->filterType == "" &&
            $this->filterStatus == "" &&
            $this->filterDate == "" &&
            $this->filterLatitude == "" &&
            $this->filterLongitude == "" &&
            $this->filterRadius == "" &&
            $this->filteKkeyword == "" 
        ){
             $this->appliedFilters = [] ;
        }else{
            if($this->filterType != "") $this->appliedFilters['type'] =  $this->filterType ;
            if($this->filterStatus != "") $this->appliedFilters['status'] = $this->filterStatus ;
            if($this->filterDate != "") $this->appliedFilters['date'] = $this->filterDate ;
            if($this->filterLatitude != "") $this->appliedFilters['coordinate']['lat'] = $this->filterLatitude ;
            if($this->filterLongitude != "") $this->appliedFilters['coordinate']['lng'] = $this->filterLongitude ;
            if($this->filterRadius != "") $this->appliedFilters['coordinate']['radius'] = $this->filterRadius ;
            if($this->filteKkeyword != "") $this->appliedFilters['keyword'] = $this->filteKkeyword  ;
            
        }
        $this->appliedFilters['per_page'] = 20 ;
        $this->resetPage();
    }

    public function selectIssue($id)
    {
        $this->selectedIssue = (new RoadissueService())->get($id) ;
        $this->urlparams = [
               
                'lat' => $this->selectedIssue["latitude"],
                'lng' => $this->selectedIssue["longitude"],
                'radius' => 1,
        ];


        $this->endpoint = route('map-incidents') ;

        $this->updateIframeUrl() ;

    }

    public function updating($property)
    {
        if (in_array($property, ['filterType', 'filterStatus', 'filterDate', 'filterLatitude', 'filterLongitude', 'filterRadius'])) {
            $this->resetPage();
        }
    }

    public function delete($id)
    {
        $user = (new RoadissueService())->get($id) ;
        $user->delete(); // refresh
        session()->flash('message', 'User '.$user->id.' deleted successfully.');
    }
    

    public function updated($field)
    {
        if (in_array($field, ['filterType', 'filterStatus', 'filterDate', 'filterLatitude', 'filterLongitude', 'filterRadius'])) {
            $this->loadRoadIssues();
        }
    }

    public function loadRoadIssues()
    {

        $page = (new RoadissueService())->fetchRoadIssues($this->appliedFilters);
        
        $collection = collect($page['data']);

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $collection,
            $page['total'] ,
            $page['per_page'] ?? 10,
            $page['current_page'] ?? 1,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function updateIframeUrl()
    {
        $params = http_build_query($this->urlparams);
        $this->iframeUrl = $this->endpoint  . '?' . $params;
    }

    public function render()
    {
        $paginator = $this->loadRoadIssues() ;
        return view('livewire.list-road-issues', [
            'roadissues' => $paginator,
        ]);
    }
}
