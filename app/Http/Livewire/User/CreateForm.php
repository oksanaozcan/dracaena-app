<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use App\Models\User;
use App\Services\UserService;
use Livewire\WithFileUploads;

class CreateForm extends Component
{
    use WithFileUploads;

    public $user;
    public $name;
    public $email;

    protected $rules = [
        'name' => 'required|string|min:3',
        'email'=> 'required|string|email|unique:users,email'
    ];

    public function mount($id = null)
    {
        if($id) {
            $u = User::find($id);
            $this->user = $u;
            $this->name = $u->name;
            $this->email = $u->email;
        } else {
            $this->user = null;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submitForm(UserService $userService)
    {
        if ($this->user === null) {
            $this->validate();
            $userService->storeUser($this->name, $this->email);

            $this->emit('userAdded');
            $this->reset();
            session()->flash('success_message', 'User successfully added.');
        } else {
            $this->validate([
                'name' => 'required|string|min:3',
                'email' => 'required|string|email|unique:users,email,'.$this->user->id,
            ]);
            $userService->updateUser($this->name, $this->email);
            return redirect()->route('users.index');
        }
    }

    public function render()
    {
        return view('livewire.user.create-form');
    }
}
