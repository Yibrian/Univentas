<?php

namespace App\Livewire\Controllers;

use Livewire\Component;
use App\Models\Producto;
use Auth;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;
use App\Models\Categoria;


class DashboardController extends Component
{
    public $productos;
    public $user;

    public $producto_mas_vendido;

    public $categoria_mas_vendida;

    public $producto_promocionado;

    public function mount()
    {
        $this->productos = Producto::where('disponibilidad', 1)->where('cantidad', '>', 0)->get();
        $this->user = Auth::user();
    
        $ventaProducto = Venta::select('producto_id', DB::raw('count(*) as total_ventas'))
            ->groupBy('producto_id')
            ->orderBy('total_ventas', 'desc')
            ->first();
        
        $this->producto_mas_vendido = $ventaProducto ? Producto::find($ventaProducto->producto_id) : null;
    
        $ventaCategoria = Venta::select('productos.categoria_id', DB::raw('count(*) as total_ventas'))
            ->join('productos', 'ventas.producto_id', '=', 'productos.id')
            ->groupBy('productos.categoria_id')
            ->orderBy('total_ventas', 'desc')
            ->first();
    
        $this->categoria_mas_vendida = $ventaCategoria ? Categoria::find($ventaCategoria->categoria_id) : null;
    
        $this->producto_promocionado = Producto::find('f8584a2f-cd39-4d6c-a5a3-b0534d723614');
    
    }
    
    public function render()
    {
        return view('dashboard')->layout('layouts.app');
    }

}