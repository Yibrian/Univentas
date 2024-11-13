<?php

namespace App\Livewire\Controllers;

use Livewire\Component;
use App\Models\Categoria;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;


class CategoriaController extends Component
{
    use WithFileUploads;

    public $categorias;
    public $selectCategoria;
    public $nombre = "";

    public $photo;

    public function mount()
    {
        $this->categorias = Categoria::all();

    }
    public function render()
    {
        return view('livewire.categoria.index')->layout('layouts.app');
    }

    public function reload()
    {
        $this->redirect(route('categorias', absolute: false), navigate: true);
    }

    public function storeCategoria()
    {
        $validator = Validator::make($this->all(), [
            'nombre' => ['required', 'string', 'max:255', 'unique:categorias,nombre'],
            'photo' => 'required|image|max:5000',
        ]);

        $validated = $validator->validated();

        $validated['nombre'] = strtoupper($validated['nombre']);

        if ($this->photo) {
            $validated['photo'] = $this->photo->store('categorias', 'public');
        }

        $categoria = Categoria::create($validated);

        $this->dispatch('toast', ['title' => __('La categoría ha sido creada!'), 'type' => 'success', 'message' => '']);
        $this->dispatch('reload');
    }

    public function viewCategoria($categoriaId)
    {
        $this->selectCategoria = Categoria::findOrFail($categoriaId);
        $this->nombre = $this->selectCategoria->nombre;
    }

    public function updateCategoria()
    {
        $categoria = $this->selectCategoria;

        $validator = Validator::make($this->all(), [
            'nombre' => ['required', 'string', 'max:255'],
            'photo' => 'nullable|image|max:5000',
        ]);

        $validated = $validator->validated();

        $validated['nombre'] = strtoupper($validated['nombre']);

        if ($validated['photo']) {
            if ($categoria->photo && file_exists(public_path('storage/' . $categoria->photo))) {
                unlink(public_path('storage/' . $categoria->photo));
            }

            $validated['photo'] = $this->photo->store('categorias', 'public');
        } else {
            $validated['photo'] = $categoria->photo;
        }

        $categoria->update($validated);

        $this->dispatch('toast', ['title' => __('La categoría ha sido actualizada!'), 'type' => 'success', 'message' => '']);
        $this->dispatch('reload');
    }


}
