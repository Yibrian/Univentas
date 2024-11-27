<?php

use Livewire\Volt\Component;

new class extends Component {
    public $notificaciones;

    public function mount()
    {
        $this->notificaciones = auth()->user()->notifications()->orderByRaw('read_at IS NULL DESC, created_at DESC')->get();
    }

    public function marcarComoLeida($id)
    {
        auth()->user()->notifications()->find($id)->markAsRead();
        $this->notificaciones = auth()->user()->notifications()->orderByRaw('read_at IS NULL DESC, created_at DESC')->get();
    }

    public function eliminarNotificacion($id)
    {
        auth()->user()->notifications()->find($id)->delete();
        $this->notificaciones = auth()->user()->notifications()->orderByRaw('read_at IS NULL DESC, created_at DESC')->get();
    }
    public function marcarComoLeidaYRedirigir($id, $url)
    {
        $notificacion = auth()->user()->notifications()->find($id);

        if ($notificacion) {
            $notificacion->markAsRead();
        }
        $this->notificaciones = auth()->user()->notifications()->orderByRaw('read_at IS NULL DESC, created_at DESC')->get();

        return redirect($url);
    }
};
?>

<div x-data="{ abierto: false }" class="relative m-2">
    <button @click="abierto = !abierto"
        class="relative flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full hover:bg-gray-300 focus:outline-none">
        <span class="text-sm">🔔</span>
        <span
            class="absolute top-0 right-0 bg-red-500 text-white rounded-full text-[10px] font-bold px-1.5 py-0.5 transform translate-x-1/2 -translate-y-1/2">
            {{ count($notificaciones->whereNull('read_at')) }}
        </span>
    </button>

    <div x-show="abierto" @click.away="abierto = false" x-transition
        class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg overflow-hidden z-50">
        <div class="p-3">
            <h2 class="text-base font-semibold text-gray-700 mb-2">Notificaciones</h2>
            <ul class="divide-y divide-gray-200">
                @forelse ($notificaciones as $notificacion)
                    <li
                        class="flex mt-1 items-start justify-between p-2 hover:bg-gray-100 {{ $notificacion->read_at ? 'bg-gray-50' : 'bg-yellow-50' }} rounded-md">
                        <div class="flex-1">
                            <a href="{{ $notificacion->data['url'] }}"
                                class="text-blue-600 font-medium text-sm hover:underline"
                                wire:click.prevent="marcarComoLeidaYRedirigir('{{ $notificacion->id }}', '{{ $notificacion->data['url'] }}')">
                                {{ $notificacion->data['titulo'] }}
                            </a>

                            </a>
                            <p class="text-gray-600 text-xs mt-1">{{ $notificacion->data['mensaje'] }}</p>
                            <p class="text-gray-500 text-xs mt-1">{{ $notificacion->created_at->format('d M Y, H:i') }}
                            </p>
                            @if (!$notificacion->read_at)
                                <button wire:click="marcarComoLeida('{{ $notificacion->id }}')"
                                    class="text-xs text-green-600 mt-1 hover:underline focus:outline-none">
                                    Marcar como leída
                                </button>
                            @endif
                        </div>
                        <button wire:click="eliminarNotificacion('{{ $notificacion->id }}')"
                            class="text-gray-400 hover:text-red-500 focus:outline-none text-sm ml-2">
                            ✖
                        </button>
                    </li>
                @empty
                    <li class="p-2 text-gray-500 text-center text-sm">
                        No tienes nuevas notificaciones.
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

</div>
