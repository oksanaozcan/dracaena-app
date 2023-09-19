<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use App\Models\User;
use App\Services\UserService;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Mail;
use App\Mail\User\PasswordMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;

class CreateForm extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;

    public $user;
    public $name;
    public $email;
    public $roleName;

    protected $rules = [
        'name' => 'required|string|min:3',
        'email'=> 'required|string|email|unique:users,email',
        'roleName' => 'required',
    ];

    public function mount($id = null)
    {
        if($id) {
            $u = User::find($id);
            $this->user = $u;
            $this->name = $u->name;
            $this->email = $u->email;
            $this->roleName = $u->roles[0]->name;
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
            $this->authorize('create', User::class);
            $this->validate();
            $arr = $userService->storeUser($this->name, $this->email, $this->roleName);

            Mail::to($this->email)->send(new PasswordMail($arr[0]));

            event(new Registered($arr[1]));

            $this->emit('userAdded');
            $this->reset();
            session()->flash('success_message', 'User successfully added.');
        } else {
            $this->authorize('update', $this->user);
            $this->validate([
                'name' => 'required|string|min:3',
                'email' => 'required|string|email|unique:users,email,'.$this->user->id,
                'roleName' => 'required',
            ]);
            $userService->updateUser($this->user, $this->name, $this->email, $this->roleName);
            return redirect()->route('users.index');
        }
    }

    public function render()
    {
        return view('livewire.user.create-form', [
            'roles' => DB::table('roles')->get(),
        ]);
    }
}
