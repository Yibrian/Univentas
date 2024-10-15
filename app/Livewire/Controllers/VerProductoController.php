<?php

namespace App\Livewire\Controllers;

use Livewire\Component;
use App\Models\Producto;



class VerProductoController extends Component{
    public $producto;

    public function mount($id)
    {
        $this->producto = Producto::findOrFail($id);

    }
    public function render()
    {
        return view('livewire.producto.ver')->layout('layouts.app');
    }

}