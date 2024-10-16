<?php

namespace App\Livewire\Controllers;

use Livewire\Component;
use App\Models\Producto;

class BusquedaController extends Component
{
    public $productos;

    public $titulo;


    public function mount($tipo, $clave)
    {
        if ($tipo == 'categoria') {
            $this->buscarPorCategoria($clave);
        } elseif($tipo == 'query'){
            $this->buscarPorPalabraClave($clave);
        }else {
            abort(404);
        }

    }
    public function buscarPorCategoria($nombre)
    {
        $this->productos = Producto::whereHas('categoria', function ($query) use ($nombre) {
            $query->where('nombre', $nombre)->where('disponibilidad', 1)->where('cantidad', '>', 0);
        })->get();

        $this->titulo = "Categoria " . strtolower($nombre);

    }

    public function buscarPorPalabraClave($palabraClave)
    {
        $this->productos = Producto::where(function ($query) use ($palabraClave) {
            $query->where('nombre', 'like', '%' . $palabraClave . '%')->where('disponibilidad', 1)->where('cantidad', '>', 0)
                ->orWhere('descripcion', 'like', '%' . $palabraClave . '%')->where('disponibilidad', 1)->where('cantidad', '>', 0);
        })->get();

        $this->titulo = "Resultados de la búsqueda: " . strtolower($palabraClave);
    }
    public function render()
    {
        return view('livewire.busqueda.index')->layout('layouts.app');
    }
}
