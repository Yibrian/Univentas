<?php

namespace App\Livewire\Controllers;

use Livewire\Component;
use App\Models\Producto;

class BusquedaController extends Component
{
    public $productos;

    public $titulo;

    public function mount($tipo, $clave){
        if($tipo == 'categoria'){
            $this->buscarPorCategoria($clave);
        }else{
            abort(404);
        }
    }
    public function buscarPorCategoria($nombre)
    {
        $this->productos = Producto::whereHas('categoria', function ($query) use ($nombre) {
            $query->where('nombre', $nombre);
        })->get();

        $this->titulo = "categoria ". strtolower($nombre);


    }
    public function render()
    {
        return view('livewire.busqueda.index')->layout('layouts.app');
    }
}
