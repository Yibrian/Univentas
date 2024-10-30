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

        $this->producto_mas_vendido = Venta::select('producto_id', DB::raw('count(*) as total_ventas'))->groupBy('producto_id')->orderBy('total_ventas', 'desc')->first();
        $this->producto_mas_vendido = Producto::findOrFail($this->producto_mas_vendido->producto_id);

        $this->categoria_mas_vendida = Venta::select('productos.categoria_id', DB::raw('count(*) as total_ventas'))->join('productos', 'ventas.producto_id', '=', 'productos.id')->groupBy('productos.categoria_id')->orderBy('total_ventas', 'desc')->first();

        $this->categoria_mas_vendida = Categoria::findOrFail($this->categoria_mas_vendida->categoria_id);

        $this->producto_promocionado = Producto::findOrFail('18084d44-e726-40d8-b81f-d38fe0e33ab5');


        // dd(vars: $this->categoria_mas_vendida);


    }
    public function render()
    {
        return view('dashboard')->layout('layouts.app');
    }

}