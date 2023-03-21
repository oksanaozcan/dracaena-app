<?php

namespace App\Http\Livewire\Ui;

use Livewire\Component;

class Dropdown extends Component
{
  public $faIcon = '';
  public $title = '';
  public $links = [];  

  public function mount($faIcon, $title, $links)
  {
    $this->faIcon = $faIcon;
    $this->mainLink = $title;
    $this->links = $links;
  }

  public function render()
  {
    return view('livewire.ui.dropdown');
  }
}
