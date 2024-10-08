<?php

namespace App\Livewire\Controllers;

use Auth;
use Livewire\Component;

class VendedorController extends Component
{
    public function mount(){
        $user = Auth::user();

        if(!$user->vendedor){
            abort(403, "No tienes acceso");
        }


        
    }
    public function render()
    {
        return view('livewire.vendedor.index')->layout('layouts.app');
    }
}
