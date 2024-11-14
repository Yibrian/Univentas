<?php

namespace App\Livewire\Controllers;

use Auth;
use Livewire\Component;
use App\Models\Venta;
use Illuminate\Support\Facades\Storage;
use App\Models\Review;

class ComprasController extends Component
{
    public $compras;
    public $user;

    public $venta;
    public $total_comprado;

    public $filtro;

    public $comentario, $estrellas;

    public function mount()
    {
        $this->user = Auth::user();
        if (!$this->user->cliente) {
            return redirect('profile');
        }
        $this->compras = $this->user->cliente->compras;
        $this->total_comprado = $this->user->cliente->compras->where('confirmacion_vendedor', true)->where('confirmacion_cliente', true)->sum('valor');



    }

    public function saveReview()
    {
        $this->validate([
            'estrellas' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500',
        ]);

        Review::create([
            'cliente_id' => $this->user->cliente->id,
            'vendedor_id' => $this->venta->vendedor->id,
            'producto_id' => $this->venta->producto->id,
            'venta_id' => $this->venta->id,
            'comentario' => $this->comentario,
            'estrellas' => $this->estrellas,
        ]);

        $this->dispatch('toast', ['title' => __('¡Se ha registrado tu calificación!'), 'type' => 'success', 'message' => '']);
        $this->dispatch('reload');
    }

    public function compraReview($id_compra)
    {
        $this->venta = Venta::findOrFail($id_compra);

        $review = Review::where('venta_id', $this->venta->id)
            ->where('cliente_id', $this->user->cliente->id)
            ->first();

        if ($review) {
            $this->comentario = $review->comentario;
            $this->estrellas = $review->estrellas;
        } else {
            $this->comentario = '';
            $this->estrellas = null;
        }
    }

    public function verReview($reseñaExistente)
    {
        $review = Review::findOrFail($reseñaExistente);
        $this->comentario = $review->comentario;
        $this->estrellas = $review->estrellas;

    }

    public function resetReviews(){
        $this->comentario = null;
        $this->estrellas = null;
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
        if ($this->venta->producto->cantidad < 0) {
            $this->dispatch('alert', ['title' => __('Ocurrio un error en la compra, supera la cantidad disponible del producto'), 'type' => 'error', 'message' => '']);
            return;
        }
        if ($this->venta->producto->cantidad == 0) {
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

    public function resetReview()
    {
        $this->reset(['comentario', 'estrellas']);
        $this->venta = null;
    }
    public function render()
    {
        return view('livewire.compras.index')->layout('layouts.app');
    }
}
