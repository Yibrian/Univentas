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




class VerProductoController extends Component{

    use WithFileUploads;

    public $producto;
    public $user;
    public $cantidad ;
    public $entrega_domicilio;
    public $lugar_entrega;
    public $metodo_pago;
    public $comprobante;
    public $valor;
    public $reviews;

    public $calificacion;

    public function mount($id)
    {
        $this->producto = Producto::findOrFail($id);
        $this->user = Auth::user();

        $this->reviews = $this->producto->reviews;
        
        if($this->reviews and $this->reviews->count() >0){
            $this->calificacion = $this->reviews->sum('estrellas') / $this->reviews->count();
        }else{
            $this->calificacion = 0;
        }


    }

    public function registrarVenta()
    {
        $vendedor = $this->producto->vendedor;
        $cliente = $this->user->cliente;
        if($this->cantidad <= 0){
            $this->dispatch('toast', ['title' => __('Cantidad no valida.'), 'type' => 'info', 'message' => '']);
            return;
        }
        if($vendedor->user->cliente == $cliente){
            $this->dispatch('toast', ['title' => __('No puedes comprar un producto de tú misma tienda.'), 'type' => 'info', 'message' => '']);
            return;
        }

        

        if(!$this->entrega_domicilio){
            $this->lugar_entrega = $vendedor->lugar_tienda;
            $this->entrega_domicilio = 0;
        }else{
            $this->producto->precio = $this->producto->precio + $this->producto->precio_domicilio;
            $this->entrega_domicilio = 1;
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

        ]);
        if($this->comprobante){
            $this->comprobante = $this->comprobante->store('comprobantes', 'public');

        }


        $this->valor = $this->producto->precio * $this->cantidad;

        $validated = $validator->validated();

        $validated['comprobante'] = $this->comprobante;
        $validated['valor'] = $this->valor;

        $venta = Venta::create($validated);
    
       
        $this->dispatch('alert', ['title' => __('Tu solicitud de compra ha sido enviada.'), 'type' => 'success', 'message' => '']);
        $this->dispatch('reload');        
    
    }

    public function resetInputs(){
        $this->reset(['cantidad', 'entrega_domicilio', 'lugar_entrega', 'metodo_pago', 'comprobante']);
    }

    public function render()
    {
        return view('livewire.producto.ver')->layout('layouts.app');
    }

}