<?php

namespace App\Livewire\Controllers;

use Livewire\Component;
use App\Models\Producto;



class DashboardController extends Component{
    public $productos;

    public function mount()
    {
        $this->productos = Producto::where('disponibilidad', 1)->where('cantidad', '>', 0)->get();
    }
    public function render()
    {
        return view('dashboard')->layout('layouts.app');
    }

}