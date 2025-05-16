<?php

namespace App\Livewire;

use App\Services\RoadissueService;
use Livewire\Component;
use Livewire\WithPagination;


class ListRoadIssues extends Component
{
    use WithPagination; // Include the WithPagination trait

    public $search = ''; // Property for search functionality
    public $selectedIssue = null;
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
        
        $this->resetPage();
    }

    public function selectIssue($id)
    {
        $this->selectedIssue = (new RoadissueService())->get($id) ;
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
            $this->loadUsers();
        }
    }

    public function loadUsers()
    {

        $page = (new RoadissueService())->fetchRoadIssues($this->appliedFilters);
        $collection = collect($page['data']);

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $collection,
            $page['total'] ?? $collection->count(),
            $page['per_page'] ?? 10,
            $page['current_page'] ?? 1,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function render()
    {
        $paginator = $this->loadUsers() ;
        return view('livewire.list-road-issues', [
            'roadissues' => $paginator,
        ]);
    }
}
