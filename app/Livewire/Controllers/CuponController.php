<?php

namespace App\Livewire\Controllers;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use App\Models\Cupon;
use Auth;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
class CuponController extends Component
{
    public $cupones;
    public $codigo;
    public $descuento;
    public $tipo;
    public $usos;
    public $fecha_expiracion;
    public $SelectCupon;

    public function mount(){
        $user =  Auth::user();
        $this->cupones =  $user->vendedor->cupones;
    }


    public function store(){
        $validator = Validator::make($this->all(), [
            'codigo' => ['required', 'string', 'max:255', 'unique:cupones,codigo'],
            'descuento' => ['required', 'numeric', 'min:0'],
            'tipo' => ['required', 'in:porcentaje,monto'], 
            'usos' => ['required', 'integer', 'min:1'], 
            'fecha_expiracion' => ['required', 'date', 'after:today'],
        ]);
        


        $validated = $validator->validated();


        $validated['codigo'] = strtoupper($validated['codigo']);
        $validated['vendedor_id'] = Auth::user()->vendedor->id;

        $cupon = Cupon::create($validated);
        $cupon->save();



        $this->dispatch('alert', ['title' => __('El cupón se ha registrado'), 'type' => 'success', 'message' => '']);
        $this->dispatch('reload');
    }

    public function viewCupon($id){
        $this->SelectCupon = Cupon::findOrFail($id);
        $this->codigo =  $this->SelectCupon->codigo;
        $this->descuento =  $this->SelectCupon->descuento;
        $this->tipo =  $this->SelectCupon->tipo;
        $this->usos =  $this->SelectCupon->usos;
        $this->fecha_expiracion =  $this->SelectCupon->fecha_expiracion;
    
    }
    public function updateCupon()
{
    $cupon = $this->SelectCupon; 
    
    $validated = $this->validate([
        'codigo' => ['required', 'string', 'max:255', Rule::unique('cupones', 'codigo')->ignore($cupon->id)],
        'descuento' => ['required', 'numeric', 'min:0'],
        'tipo' => ['required', 'in:porcentaje,monto'],
        'usos' => ['required', 'integer', 'min:1'],
        'fecha_expiracion' => ['required', 'date', 'after:today'],
    ]);

    $validated['codigo'] = strtoupper($validated['codigo']);

    $cupon->fill($validated);
    $cupon->save();

    $this->dispatch('alert', [
        'title' => __('El cupón se ha actualizado'),
        'type' => 'success',
        'message' => '',
    ]);

    $this->dispatch('reload');
}

    public function render()
    {
        return view('livewire.ventas.cupones')->layout('layouts.app');
    }
    public function reload()
    {
        $this->redirect(route('cupones', absolute: false), navigate: true);
    }
}
