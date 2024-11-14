<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <!-- Carrusel -->
            <div wire:ignore>
                <div x-data="carousel()" class="relative bg-white shadow sm:rounded-lg">
                    <div class="overflow-hidden">
                        <template x-for="(slide, index) in slides" :key="index">
                            <div x-show="currentSlide === index" class="transition-opacity duration-500">
                                <!-- Ajuste en el tamaño de la imagen -->
                                <img :src="slide.image" alt="" class="w-full h-64 object-cover"> <!-- Cambié 'object-contain' a 'object-cover' para que la imagen cubra todo el espacio disponible -->
                                <div class="absolute bottom-0 left-0 right-0 bg-opacity-50 bg-gray-800 text-white p-4">
                                    <h3 class="text-lg font-semibold" x-text="slide.text"></h3>
                                    <button @click="goToUrl(slide.url)"
                                        class="mt-2 bg-red-600 text-white py-2 px-4 rounded"
                                        x-text="slide.buttonText"></button>
                                </div>
                            </div>
                        </template>
                    </div>
            
                    <!-- Botones de navegación del carrousel -->
                    <button @click="prevSlide"
                        class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white p-2 rounded-md">
                        &lt;
                    </button>
                    <button @click="nextSlide"
                        class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white p-2 rounded-md">
                        &gt;
                    </button>
            
                    <!-- Indicadores de la imagen activa -->
                    <div class="flex justify-center mt-4">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button @click="currentSlide = index" class="h-2 w-2 mx-1 rounded-full"
                                :class="{ 'bg-gray-800': currentSlide === index, 'bg-gray-400': currentSlide !== index }"></button>
                        </template>
                    </div>
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
        Alpine.data('carousel', () => {
            const slides = [];

            @if (isset($producto_mas_vendido))
                slides.push({
                    image: @json(asset('storage/' . $producto_mas_vendido->imagen)),
                    text: 'Lo más vendido: ' + @json(ucfirst(strtolower($producto_mas_vendido->nombre))),
                    buttonText: 'Ver producto',
                    url: @json(route('producto', ['id' => $producto_mas_vendido->id]))
                });
            @endif

            @if (isset($categoria_mas_vendida))
                slides.push({
                    image: @json(asset('storage/' . $categoria_mas_vendida->photo)),
                    text: 'Lo más exitoso: ' + @json(ucfirst(strtolower($categoria_mas_vendida->nombre))),
                    buttonText: 'Ver categoría',
                    url: @json(route('busqueda', ['tipo' => 'categoria', 'clave' => $categoria_mas_vendida->nombre]))
                });
            @endif

            @if (isset($producto_promocionado))
                slides.push({
                    image: @json(asset('storage/' . $producto_promocionado->imagen)),
                    text: 'Producto promocionado: ' + @json(ucfirst(strtolower($producto_promocionado->nombre))),
                    buttonText: 'Ver producto',
                    url: @json(route('producto', ['id' => $producto_promocionado->id]))
                });
            @endif

            return {
                currentSlide: 0,
                slides: slides,
                nextSlide() {
                    if (this.slides.length > 0) {
                        this.currentSlide = (this.currentSlide + 1) % this.slides.length;
                    }
                },
                prevSlide() {
                    if (this.slides.length > 0) {
                        this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides
                            .length;
                    }
                },
                goToUrl(url) {
                    window.open(url, '_blank');
                },
                startAutoSlide() {
                    if (this.slides.length > 0) {
                        setInterval(() => {
                            this.nextSlide();
                        }, 4000);
                    }
                },
                init() {
                    this.startAutoSlide();
                }
            };
        });
    });
</script>
