<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Livewire\Modal;

class CategoryModal extends Modal
{
    public function render()
    {
        return view('livewire.category-modal');
    }
}
