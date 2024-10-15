<?php

namespace App\Livewire\Controllers;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Component
{
    use WithFileUploads;

    public $productos;
    public $categorias;
    public $selectProducto;
    public $nombre;
    public $descripcion;
    public $precio;
    public $cantidad;
    public $disponibilidad;
    public $imagen;
    public $categoria_id;


    public function mount()
    {
        $user = Auth::user();

        if (!$user->vendedor) {
            abort(403, "No tienes acceso");
        }

        $this->productos = $user->vendedor->productos;
        $this->categorias = Categoria::all();

    }

    public function store()
    {
        $validator = Validator::make($this->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'precio' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:0',
            'disponibilidad' => 'required|boolean',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'required|image|max:2048'
        ]);

        $validated = $validator->validated();

        $validated['nombre'] = strtoupper($validated['nombre']);
        $validated['vendedor_id'] = Auth::user()->vendedor->id;


        if ($this->imagen) {
            $validated['imagen'] = $this->imagen->store('productos', 'public');
        }

        $producto = Producto::create($validated);

        $this->dispatch('toast', ['title' => __('El producto se ha publicado!'), 'type' => 'success', 'message' => '']);
        $this->dispatch('reload');


    }

    public function update()
    {
        $producto = $this->selectProducto;

        $validator = Validator::make($this->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'precio' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:0',
            'disponibilidad' => 'required|boolean',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|max:2048'
        ]);

        $validated = $validator->validated();

        $validated['nombre'] = strtoupper($validated['nombre']);

        if ($validated['imagen']) {
            if ($producto->imagen && file_exists(public_path('storage/' . $producto->imagen))) {
                unlink(public_path('storage/' . $producto->imagen));
            }

            $validated['imagen'] = $this->imagen->store('productos', 'public');
        } else {
            $validated['imagen'] = $producto->imagen;
        }

        $producto->update($validated);

        $this->dispatch('toast', ['title' => __('El producto ha sido actualizada!'), 'type' => 'success', 'message' => '']);
        $this->dispatch('reload');
    }

    public function toggleDisponibilidad($producto_id)
    {
        $producto = Producto::findOrFail($producto_id);

        if ($producto->disponibilidad) {
            $producto->disponibilidad = 0;
        } else {
            $producto->disponibilidad = 1;
        }

        $producto->save();
        $this->dispatch('toast', ['title' => __('Se ha cambiado el estado!'), 'type' => 'success', 'message' => '']);
        $this->dispatch('reload');

    }


    public function viewProducto($producto_id)
    {
        $this->selectProducto = Producto::findOrFail($producto_id);
        $this->nombre = $this->selectProducto->nombre;
        $this->descripcion = $this->selectProducto->descripcion;
        $this->precio = $this->selectProducto->precio;
        $this->cantidad = $this->selectProducto->cantidad;
        $this->disponibilidad = $this->selectProducto->disponibilidad;
        $this->categoria_id = $this->selectProducto->categoria_id;
    }

    public function reload()
    {
        $this->redirect(route('vender', absolute: false), navigate: true);
    }

    public function render()
    {
        return view('livewire.producto.index')->layout('layouts.app');
    }
}
