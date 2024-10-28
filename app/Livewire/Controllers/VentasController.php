<?php

namespace App\Livewire\Controllers;

use Livewire\Component;
use Auth;
use App\Models\Venta;
use Illuminate\Support\Facades\Storage;

class VentasController extends Component
{
    public $ventas;
    public $user;

    public $venta;

    public $total_vendido;

    public $filtro;

    public function mount()
    {
        $this->user = Auth::user();
        $this->ventas = $this->user->vendedor->ventas;
        $this->total_vendido = $this->user->vendedor->ventas->where('confirmacion_vendedor', true)->where('confirmacion_cliente', true)->sum('valor');
        // dd($this->total_vendido);
    }
    public function aplicarFiltro()
    {
        switch ($this->filtro) {
            case 'confirmadas':
                $this->ventas = $this->user->vendedor->ventas->where('confirmacion_vendedor', true)
                    ->where('confirmacion_cliente', true);
                break;

            case 'pendientes':
                $this->ventas = $this->user->vendedor->ventas->where('confirmacion_vendedor', false)->where('confirmacion_cliente', false);
                break;

            case 'espera':
                $this->ventas = $this->user->vendedor->ventas->where('confirmacion_vendedor', true)->where('confirmacion_cliente', false);
                break;

            default:
                $this->ventas = $this->user->vendedor->ventas;
                break;
        }
    }

    public function confirmarVenta($id_venta)
    {
        $this->venta = Venta::findOrFail($id_venta);

        if (!$this->venta->confirmacion_vendedor) {
            $this->venta->confirmacion_vendedor = 1;
        } else {
            $this->dispatch('toast', ['title' => __('¡Esta venta ya se encuentra confirmada!'), 'type' => 'info', 'message' => '']);
            return;
        }

        $this->venta->save();
        $this->dispatch('alert', ['title' => __('Se ha confirmado la venta'), 'type' => 'success', 'message' => '']);
        $this->ventas = $this->user->vendedor->ventas;
        $this->total_vendido = $this->user->vendedor->ventas->where('confirmacion_vendedor', true)->where('confirmacion_cliente', true)->sum('valor');

    }

    public function cancelarVenta($id_venta)
    {
        $this->venta = Venta::findOrFail($id_venta);

        if (!$this->venta->confirmacion_vendedor && !$this->venta->confirmacion_cliente) {
            if ($this->venta->comprobante && Storage::disk('public')->exists($this->venta->comprobante)) {
                Storage::disk('public')->delete($this->venta->comprobante);
            }

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
