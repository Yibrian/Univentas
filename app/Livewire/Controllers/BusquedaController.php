<?php

namespace App\Livewire\Controllers;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Vendedor;
use App\Models\Categoria;

class BusquedaController extends Component
{
    public $productos;

    public $titulo;

    public $vendedor;

    public $categoria;


    public function mount($tipo, $clave)
    {
        switch ($tipo) {
            case 'categoria':
                $this->buscarPorCategoria($clave);
                break;
            case 'query':
                $this->buscarPorPalabraClave($clave);
                break;
            case 'tienda':
                $this->buscarPorTienda($clave);
                break;

            default:
                abort(code: 404);
                break;
        }


    }
    public function buscarPorCategoria($nombre)
    {
        $this->productos = Producto::whereHas('categoria', function ($query) use ($nombre) {
            $query->where('nombre', $nombre)->where('disponibilidad', 1)->where('cantidad', '>', 0);
        })->get();

        $this->titulo = "Categoria " . strtolower($nombre);
        $this->categoria = Categoria::where('nombre', $nombre)->first();

    }

    public function buscarPorPalabraClave($palabraClave)
    {
        $this->productos = Producto::where(function ($query) use ($palabraClave) {
            $query->where('nombre', 'like', '%' . $palabraClave . '%')->where('disponibilidad', 1)->where('cantidad', '>', 0)
                ->orWhere('descripcion', 'like', '%' . $palabraClave . '%')->where('disponibilidad', 1)->where('cantidad', '>', 0);
        })->get();

        $this->titulo = "Resultados de la búsqueda: " . strtolower($palabraClave);
    }


    public function buscarPorTienda($id){
        $this->vendedor = Vendedor::findOrFail($id);
        $this->productos = $this->vendedor->productos->where('disponibilidad', 1)->where('cantidad', '>', 0);
        $this->titulo = "Resultados de la tienda: ". strtolower($this->vendedor->nombre_tienda);
    }
    public function render()
    {
        return view('livewire.busqueda.index')->layout('layouts.app');
    }
}
