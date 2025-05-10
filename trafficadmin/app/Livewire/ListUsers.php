<?php

namespace App\Livewire;

use App\Services\UserService;
use Livewire\Component;
use Livewire\WithPagination;


class ListUsers extends Component
{
    use WithPagination; // Include the WithPagination trait

    public $search = ''; // Property for search functionality


    public function render()
    {
        //  $annonces = (new UserService())->search($this->search)->paginate(8) ;
        $users = (new UserService())->getAll() ;
        return view('livewire.list-users', [
            'users' => $users,
        ]);
    }

    
    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination when search query changes
    }


}
