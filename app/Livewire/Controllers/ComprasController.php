<?php

namespace App\Livewire\Controllers;

use Auth;
use Livewire\Component;
use App\Models\Venta;
class ComprasController extends Component
{
    public $compras;
    public $user;

    public $venta;

    //'cantidad', 'entrega', 'direccion', 'metodo', 'comprobante', 'valor'];



    public function mount()
    {
        $this->user = Auth::user();
        if (!$this->user->cliente) {
            return redirect('profile');
        }
        $this->compras = $this->user->cliente->compras;
    }

    public function confirmarRecepción($id_venta)
    {
        $this->venta = Venta::findOrFail($id_venta);

        if (!$this->venta->confirmacion_vendedor) {
            $this->dispatch('toast', ['title' => __('Esta venta aún no ha sido confirmada por el vendedor'), 'type' => 'info', 'message' => '']);
            return;
        }

        if (!$this->venta->confirmacion_cliente) {
            $this->venta->confirmacion_cliente = 1;
        } else {
            $this->dispatch('toast', ['title' => __('¡Esta venta ya se encuentra confirmada!'), 'type' => 'info', 'message' => '']);
            return;
        }

        $this->venta->save();
        $this->dispatch('alert', ['title' => __('Se ha confirmado la recepción'), 'type' => 'success', 'message' => '']);
        $this->compras = $this->user->cliente->compras;

    }

    public function cancelarCompra($id_venta)
    {
        $this->venta = Venta::findOrFail($id_venta);

        if (!$this->venta->confirmacion_vendedor && !$this->venta->confirmacion_cliente) {
            $this->venta->delete();
            $this->dispatch('alert', ['title' => __('Se ha cancelado la compra'), 'type' => 'info', 'message' => '']);
            $this->compras = $this->user->cliente->compras;
        } else {
            $this->dispatch('alert', ['title' => __('No se puede cancelar la compra, ya ha sido confirmada.'), 'type' => 'error', 'message' => '']);
            return;
        }

    }
    public function render()
    {
        return view('livewire.compras.index')->layout('layouts.app');
    }
}
