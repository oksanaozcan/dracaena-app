<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Table extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $search = '';

    public $selectedUser;

    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $checkedNames = [];

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    protected $listeners = [
        'userAdded' => 'render',
        'deletedUsers' => 'render'
    ];

    public function updated()
    {
        $this->emit('checkedNamesUpdated', $this->checkedNames);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function editUser($id)
    {
        return redirect()->route('users.edit', $id);
    }

    public function destroyUser($id, UserService $userService)
    {
        $user = User::find($id);
        $this->authorize('delete', $user);
        $userService->destroyUser($user);
        $this->emit('deletedUsers');
    }

    public function sortBy($columnHeader)
    {
        if ($this->sortField === $columnHeader) {
            $this->sortDirection = $this->swapSortDirection();
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $columnHeader;
    }

    public function swapSortDirection()
    {
        return $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function render(UserService $userService)
    {
        $users = $userService->searchForTable($this->search, $this->sortField, $this->sortDirection);
        return view('livewire.user.table', [
            'users' => $users,
            'count' => User::count()
        ]);
    }

    public function selectUser(int $userId)
    {
        $this->selectedUser = $userId;
    }
}
