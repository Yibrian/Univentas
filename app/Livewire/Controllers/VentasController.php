<?php

namespace App\Livewire\Controllers;

use Livewire\Component;


class VentasController extends Component
{
    
    
    public function render()
    {
        return view('livewire.ventas.index')->layout('layouts.app');
    }
}
