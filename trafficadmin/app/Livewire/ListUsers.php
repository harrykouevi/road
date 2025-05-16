<?php

namespace App\Livewire;

use App\Services\UserService;
use Livewire\Component;
use Livewire\WithPagination;


class ListUsers extends Component
{
    use WithPagination; // Include the WithPagination trait

    public $search = ''; // Property for search functionality

    public $users;

    public $selectedUser = null;



    public function selectUser($id)
    {
        $this->selectedUser = (new UserService())->get($id) ;
  
    }

    public function mount()
    {
        $this->users = (new UserService())->getAll() ;
    }

    public function render()
    {
        return view('livewire.list-users');
    }

    
    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination when search query changes
    }

    public function delete($id)
    {
        $user = (new UserService())->get($id) ;
        $user->delete(); // refresh
        session()->flash('message', 'User '.$user->id.' deleted successfully.');
    }


}
