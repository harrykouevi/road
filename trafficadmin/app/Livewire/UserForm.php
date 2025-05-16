<?php

namespace App\Livewire;

use App\Services\UserService;
use Livewire\Component;

class UserForm extends Component
{
    public $user;
    public $userId;
    public $name;
    public $email;


    public function mount($id = null)
    {
        if ($id) {
            $this->user = $user = (new UserService())->get($id) ;
            $this->userId = $user['id'];
            $this->name = $user['name'];
            $this->email = $user['email'];
        }
    }

    public function save()
    {
        $data = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        if ($this->userId) {
            (new UserService())->update($this->userId,$data) ; 
            session()->flash('message', 'User updated.');
        } else {
            // User::create($data);
            session()->flash('message', 'User created.');
        }

        return redirect()->route('getusers');
    }


    public function render()
    {
        return view('livewire.user-form');
    }
}
