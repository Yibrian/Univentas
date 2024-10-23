<?php

namespace App\Livewire\Controllers;

use Livewire\Component;
use Auth;
use App\Models\Venta;
class VentasController extends Component
{
    public $ventas;
    public $user;

    public $venta;

    public function mount(){
        $this->user =  Auth::user();
        $this->ventas = $this->user->vendedor->ventas;
    }

    public function confirmarVenta($id_venta){
        $this->venta = Venta::findOrFail($id_venta);

        if(!$this->venta->confirmacion_vendedor){
            $this->venta->confirmacion_vendedor = 1;
        }else{
            $this->dispatch('toast', ['title' => __('¡Esta venta ya se encuentra confirmada!'), 'type' => 'info', 'message' => '']);
            return;
        }

        $this->venta->save();
        $this->dispatch('alert', ['title' => __('Se ha confirmado la venta'), 'type' => 'success', 'message' => '']);
        $this->ventas = $this->user->vendedor->ventas;
    }

    public function cancelarVenta($id_venta){
        $this->venta = Venta::findOrFail($id_venta);

        if (!$this->venta->confirmacion_vendedor && !$this->venta->confirmacion_cliente) {
            $this->venta->delete();
            $this->dispatch('alert', ['title' => __('La venta ha sido cancelada correctamente'), 'type' => 'info', 'message' => '']);
            $this->ventas = $this->user->vendedor->ventas;
        } else {
            $this->dispatch('alert', ['title' => __('No se puede cancelar la compra, ya ha sido confirmada.'), 'type' => 'error', 'message' => '']);
            return;
        }
    }


    
    public function render()
    {
        return view('livewire.ventas.index')->layout('layouts.app');
    }
}
