<?php

namespace App\Livewire\Controllers;

use Auth;
use Livewire\Component;
use App\Models\Producto;

class ComprasController extends Component
{
    public $compras;
    public $user;

    public function mount(){
        $this->user =  Auth::user();
        if(!$this->user->cliente){
            return redirect('profile');
        }
        $this->compras = $this->user->cliente->compras;
    }
    public function render()
    {
        return view('livewire.compras.index')->layout('layouts.app');
    }
}
