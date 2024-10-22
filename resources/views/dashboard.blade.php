<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <!-- Carrusel -->
            <div x-data="carousel()" class="relative">
                <div class="overflow-hidden">
                    <template x-for="(slide, index) in slides" :key="index">
                        <div x-show="currentSlide === index" class="transition-opacity duration-500">
                            <img :src="slide" alt="" class="w-full h-64 object-cover">
                        </div>
                    </template>
                </div>

                <button @click="prevSlide"
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white p-2 rounded-md">
                    &lt;
                </button>
                <button @click="nextSlide"
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white p-2 rounded-md">
                    &gt;
                </button>

                <div class="flex justify-center mt-4">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="currentSlide = index" class="h-2 w-2 mx-1 rounded-full"
                            :class="{ 'bg-gray-800': currentSlide === index, 'bg-gray-400': currentSlide !== index }"></button>
                    </template>
                </div>
            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('¡Compra con nosotros!') }}</h3>

                @if ($productos->isEmpty())
                    <p class="text-center text-gray-500">{{ __('No hay productos disponibles.') }}</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($productos as $producto)
                            @if ($user->cliente)
                                <a href="{{ route('producto', ['id' => $producto->id]) }}"
                                    class="p-4 bg-gray-50 border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 ease-in-out group cursor-pointer">
                                @else
                                    <a href="{{ route('profile') }}"
                                        class="p-4 bg-gray-50 border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 ease-in-out group cursor-pointer">
                            @endif
                            <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}"
                                class="w-full h-48 object-cover rounded-md mb-2">
                            <h4
                                class="text-lg font-semibold text-gray-600 group-hover:text-red-700 transition duration-200">
                                {{ $producto->nombre }}</h4>
                            <p class="text-gray-500 mt-2">{{ $producto->descripcion }}</p>
                            <p class="text-gray-800 font-semibold mt-4">Precio:
                                ${{ number_format($producto->precio, 0) }}</p>
                            <p class="text-gray-500 mt-1">Cantidad disponible: {{ $producto->cantidad }}</p>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>


        </div>
    </div>
    @include('components.alert-component')

</div>


<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('carousel', () => ({
            currentSlide: 0,
            slides: [
                '{{ asset('storage/images/imagen1.jpg') }}',
                '{{ asset('storage/images/imagen2.jpg') }}',
            ],
            nextSlide() {
                this.currentSlide = (this.currentSlide + 1) % this.slides.length;
            },
            prevSlide() {
                this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides
                    .length;
            },
            startAutoSlide() {
                setInterval(() => {
                    this.nextSlide();
                }, 3000);
            },
            init() {
                this.startAutoSlide();
            }
        }));
    });
</script>
