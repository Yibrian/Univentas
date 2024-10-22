<?php

namespace App\Livewire\Controllers;

use Livewire\Component;
use App\Models\Producto;
use Auth;



class DashboardController extends Component{
    public $productos;
    public $user;

    public function mount()
    {
        $this->productos = Producto::where('disponibilidad', 1)->where('cantidad', '>', 0)->get();
        $this->user = Auth::user();

        
    }
    public function render()
    {
        return view('dashboard')->layout('layouts.app');
    }

}