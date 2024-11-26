<?php

namespace App\Livewire\Controllers;

use Auth;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Support\Facades\Validator;
use App\Models\Venta;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Cupon;
use App\Notifications\NuevaNotificacion;

use App\Models\User;



class VerProductoController extends Component
{

    use WithFileUploads;

    public $producto;
    public $user;
    public $cantidad;
    public $entrega_domicilio;
    public $lugar_entrega;
    public $metodo_pago;
    public $comprobante;
    public $valor;
    public $reviews;

    public $calificacion;

    public $codigo_cupon;
    public $cupon;

    public $tipo;
    public $descuento;


    public function mount($id)
    {
        $this->producto = Producto::findOrFail($id);
        $this->user = Auth::user();

        $this->reviews = $this->producto->reviews;

        if ($this->reviews and $this->reviews->count() > 0) {
            $this->calificacion = $this->reviews->sum('estrellas') / $this->reviews->count();
        } else {
            $this->calificacion = 0;
        }


    }

    public function aplicarCupon()
    {
        $validator = Validator::make($this->all(), [
            'codigo_cupon' => 'required|string|max:255',
        ]);

        $validated = $validator->validated();

        $this->cupon = Cupon::where('codigo', $this->codigo_cupon)
            ->where('vendedor_id', $this->producto->vendedor->id)
            ->first();

        if (!$this->cupon) {
            $this->dispatch('toast', ['title' => __('No se ha encontrado el cupón.'), 'type' => 'info', 'message' => '']);
            $this->cupon = null;
            return;
        }
        if ($this->cupon->fecha_expiracion && $this->cupon->fecha_expiracion < now()) {
            $this->dispatch('toast', ['title' => __('El cupón ingresado ha expirado.'), 'type' => 'info', 'message' => '']);
            $this->cupon = null;
            return;
        }
        if ($this->cupon->usos <= 0) {
            $this->dispatch('toast', ['title' => __('El cupón ingresado ya no está disponible.'), 'type' => 'info', 'message' => '']);
            $this->cupon = null;
            return;
        }

        $this->dispatch('toast', ['title' => __('El cupón se ha aplicado correctamente.'), 'type' => 'success', 'message' => '']);

        $this->descuento = $this->cupon->descuento;
        $this->tipo = $this->cupon->tipo;





    }

    public function registrarVenta()
    {
        $vendedor = $this->producto->vendedor;
        $cliente = $this->user->cliente;

        if ($this->cantidad <= 0) {
            $this->dispatch('toast', ['title' => __('Cantidad no válida.'), 'type' => 'info', 'message' => '']);
            return;
        }

        if ($vendedor->user->cliente == $cliente) {
            $this->dispatch('toast', ['title' => __('No puedes comprar un producto de tu misma tienda.'), 'type' => 'info', 'message' => '']);
            return;
        }

        if (!$this->entrega_domicilio) {
            $this->lugar_entrega = $vendedor->lugar_tienda;
            $this->entrega_domicilio = 0;
        } else {
            $this->producto->precio += $this->producto->precio_domicilio;
            $this->entrega_domicilio = 1;
        }

        $this->valor = $this->producto->precio * $this->cantidad;

        if ($this->cupon) {
            if ($this->cupon['tipo'] === 'monto') {
                $this->valor -= $this->cupon['descuento'];
            } elseif ($this->cupon['tipo'] === 'porcentaje') {
                $this->valor -= $this->valor * ($this->cupon['descuento'] / 100);
            }
            $this->valor = max(0, $this->valor);
        }

        $datos = [
            'producto_id' => $this->producto->id,
            'vendedor_id' => $vendedor->id,
            'cliente_id' => $cliente->id,
            'cantidad' => $this->cantidad,
            'entrega_domicilio' => $this->entrega_domicilio,
            'lugar_entrega' => $this->lugar_entrega,
            'metodo' => $this->metodo_pago,
            'comprobante' => $this->comprobante,
            'valor' => $this->valor,
            'cupon_id' => $this->cupon->id ?? null,
        ];

        $validator = Validator::make($datos, [
            'producto_id' => 'required|exists:productos,id',
            'vendedor_id' => 'required|exists:vendedores,id',
            'cliente_id' => 'required|exists:clientes,id',
            'cantidad' => 'required|integer|min:0|max:' . $this->producto->cantidad,
            'entrega_domicilio' => 'nullable|boolean',
            'lugar_entrega' => ['nullable', 'string', 'max:255', Rule::requiredIf($this->entrega_domicilio === true)],
            'metodo' => 'required|string',
            'comprobante' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048', Rule::requiredIf($this->metodo_pago === 'nequi')],
            'valor' => 'required|numeric|min:0',
            'cupon_id' =>'nullable|exists:cupones,id',
        ]);

        if ($validator->fails()) {
            $this->dispatch('toast', ['title' => __('Error al validar la venta.'), 'type' => 'error', 'message' => '']);
            return;
        }

        if ($this->comprobante) {
            $this->comprobante = $this->comprobante->store('comprobantes', 'public');
        }

        $validated = $validator->validated();
        $venta = Venta::create($validated);

        $user = $vendedor->user;
        $user->notify(new NuevaNotificacion([
            'titulo' => 'Nueva Solicitud de Compra',
            'mensaje' => 'Tienes una nueva venta pendiente por confirmar. Revisa los detalles de la solicitud.',
            'url' => '/mis-ventas',
        ]));
        


        $this->dispatch('alert', ['title' => __('Tu solicitud de compra ha sido enviada.'), 'type' => 'success', 'message' => '']);
        $this->dispatch('reload');
    }


    public function resetInputs()
    {
        $this->descuento = null;
        $this->tipo = null;
        $this->cupon = null;
        $this->reset(['cantidad', 'entrega_domicilio', 'lugar_entrega', 'metodo_pago', 'comprobante', 'codigo_cupon']);
    }

    public function render()
    {
        return view('livewire.producto.ver')->layout('layouts.app');
    }

}