<?php

namespace App\Livewire\Controllers;

use Livewire\Component;
use App\Models\User;
use App\Models\Municipio;
use App\Models\Role;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;





class UsersController extends Component
{
    public $users;
    public $selectUser;
    public $name = "";
    public $email = "";
    public $cedula = "";
    public $photo = 'users/user-default.png';
    public $is_active = 1;
    public $roles;
    public $rol = "";
    public $password = '';
    public $password_confirmation = '';





    public function mount()
    {
        $this->users = User::all();
        $this->roles = Role::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        //$this->roles = Role::whereNotIn('name', ['elector', 'gestor', 'lider'])->pluck('name', 'id')->toArray();


    }
    public function reload()
    {
        $this->redirect(route('users', absolute: false), navigate: true);
    }
    public function storeUser()
    {
        $validator = Validator::make($this->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'lowercase', 'max:255', 'regex:/^[a-zA-Z0-9._%+-]+@correounivalle\.edu.co$/', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'is_active' => ['required', 'boolean'],
            'rol' => ['required', 'exists:roles,id'],
            'cedula' => 'required|integer|min:1|unique:users',
        ]);


        $validated = $validator->validated();


        $validated['password'] = Hash::make($validated['password']);
        $validated['name'] = strtoupper($validated['name']);

        $validated['photo'] = $this->photo;

        $user = User::create($validated);
        $role = Role::findOrFail($this->rol);
        $user->assignRole($role);
        $user->save();


        event(new Registered($user));


        $this->dispatch('alert', ['title' => __('User created successfully'), 'type' => 'success', 'message' => '']);
        $this->dispatch('reload');
    }

    public function updateUser()
    {
        $user = $this->selectUser;
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'password' => ['nullable', 'string', 'confirmed', Rules\Password::defaults()],
            'is_active' => ['required', 'boolean'],
            'rol' => ['nullable', 'exists:roles,id'],
            'cedula' => ['required', 'integer', 'min:1', Rule::unique(User::class)->ignore($user->id)],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }


        $user->fill($validated);
        $user->save();


        $this->dispatch('alert', ['title' => __('User successfully updated'), 'type' => 'success', 'message' => '']);
        $this->dispatch('reload');
    }

    public function viewUser($userId)
    {
        $this->selectUser = User::findOrFail($userId);
        $this->name = $this->selectUser->name;
        $this->email = $this->selectUser->email;
        $this->is_active = $this->selectUser->is_active;
        $this->rol = $this->selectUser->roles->first()->id ?? '';
        $this->cedula = $this->selectUser->cedula;
    }
    public function changeStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();


        $this->dispatch('toast', ['title' => __('User successfully updated'), 'type' => 'info', 'message' => '']);
        $this->dispatch('reload');
    }

    public function deleteUser(User $user)
    {
        if ($user->photo != "users/user-default.png") {
            Storage::disk('public')->delete($user->photo);
        }
        $user->delete();



        $this->dispatch('toast', ['title' => __('User successfully deleted'), 'type' => 'warning', 'message' => '']);
        $this->dispatch('reload');
    }


    public function render()
    {
        return view('livewire.user.index')->layout('layouts.app');
    }
}
