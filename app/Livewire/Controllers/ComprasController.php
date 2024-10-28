<?php

namespace App\Livewire\Controllers;

use Auth;
use Livewire\Component;
use App\Models\Venta;
use Illuminate\Support\Facades\Storage;

class ComprasController extends Component
{
    public $compras;
    public $user;

    public $venta;
    public $total_comprado;

    public $filtro;


    //'cantidad', 'entrega', 'direccion', 'metodo', 'comprobante', 'valor'];



    public function mount()
    {
        $this->user = Auth::user();
        if (!$this->user->cliente) {
            return redirect('profile');
        }
        $this->compras = $this->user->cliente->compras;
        $this->total_comprado = $this->user->cliente->compras->where('confirmacion_vendedor', true)->where('confirmacion_cliente', true)->sum('valor');

    }

    public function aplicarFiltro()
    {
        switch ($this->filtro) {
            case 'confirmadas':
                $this->compras = $this->user->cliente->compras->where('confirmacion_vendedor', true)
                    ->where('confirmacion_cliente', true);
                break;

            case 'pendientes':
                $this->compras = $this->user->cliente->compras->where('confirmacion_vendedor', false)->where('confirmacion_cliente', false);
                break;

            case 'espera':
                $this->compras = $this->user->cliente->compras->where('confirmacion_vendedor', true)->where('confirmacion_cliente', false);
                break;

            default:
                $this->compras = $this->user->cliente->compras;
                break;
        }
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

        $this->venta->producto->cantidad = $this->venta->producto->cantidad - $this->venta->cantidad;
        if($this->venta->producto->cantidad < 0){
            $this->dispatch('alert', ['title' => __('Ocurrio un error en la compra, supera la cantidad disponible del producto'), 'type' => 'error', 'message' => '']);
            return;
        }
        if($this->venta->producto->cantidad == 0){
            $this->venta->producto->disponibilidad = 0;
        }
        $this->venta->save();
        $this->venta->producto->save();
        $this->dispatch('alert', ['title' => __('Se ha confirmado la recepción'), 'type' => 'success', 'message' => '']);
        $this->compras = $this->user->cliente->compras;
        $this->total_comprado = $this->user->cliente->compras->where('confirmacion_vendedor', true)->where('confirmacion_cliente', true)->sum('valor');


    }

    public function cancelarCompra($id_venta)
    {
        $this->venta = Venta::findOrFail($id_venta);

        if (!$this->venta->confirmacion_vendedor && !$this->venta->confirmacion_cliente) {
            if ($this->venta->comprobante && Storage::disk('public')->exists($this->venta->comprobante)) {
                Storage::disk('public')->delete($this->venta->comprobante);
            }
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
