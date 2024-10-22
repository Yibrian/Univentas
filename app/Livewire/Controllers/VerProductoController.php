<?php

namespace App\Livewire\Controllers;

use Auth;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Cliente; 
use Illuminate\Support\Facades\Validator;
use App\Models\Venta;


class VerProductoController extends Component{
    public $producto;
    public $user;

    public function mount($id)
    {
        $this->producto = Producto::findOrFail($id);
        $this->user = Auth::user();

    }

    public function registrarVenta()
    {
        $vendedor = $this->producto->vendedor;
        $cliente = $this->user->cliente;

        if($vendedor->user->cliente == $cliente){
            $this->dispatch('toast', ['title' => __('No puedes comprar un producto de tú misma tienda.'), 'type' => 'info', 'message' => '']);
            return;
        }

        $datos = [
            'producto_id' => $this->producto->id,
            'vendedor_id' => $vendedor->id,
            'cliente_id' => $cliente->id,
        ];
    
        $validator = Validator::make($datos, [
            'producto_id' => 'required|exists:productos,id',
            'vendedor_id' => 'required|exists:vendedores,id',
            'cliente_id' => 'required|exists:clientes,id', 
        ]);
    
    
        $validated = $validator->validated();
        $venta = Venta::create($validated);
    
       
        $this->dispatch('alert', ['title' => __('Tu solicitud de compra ha sido enviada.'), 'type' => 'success', 'message' => '']);
        $this->dispatch('reload');        
    
    }
    public function render()
    {
        return view('livewire.producto.ver')->layout('layouts.app');
    }

}