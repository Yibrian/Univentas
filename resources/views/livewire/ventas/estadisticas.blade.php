<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard Estadísticas') }}
    </h2>
    <x-breadcrumbs :breadcrumbs="[['title' => 'Inicio', 'url' => route('dashboard')], ['title' => 'Estadísticas', 'url' => null]]" />
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white p-6 shadow sm:rounded-lg">

            @role('admin')
                <div class="w-full mt-4">
                    <h2 class="text-center text-xl font-bold mb-4">Estadísticas administrador</h2>
                    <div class="flex flex-wrap -mx-2 ">
                        <div class="w-1/2 px-2 mt-4">
                            <h2 class="text-center text-xl font-bold mb-4">Promedio ventas mensuales total</h2>
                            <x-dynamic-chart type="line" :labels="$ventas_tiempo->keys()->toArray()" :data="$ventas_tiempo->values()->toArray()" class="w-full h-full"
                                id="venta_tiempo" titulo="Ventas Mensuales" />
                        </div>
                        <div class="w-1/2 px-2 mt-4">
                            <h2 class="text-center text-xl font-bold mb-4">Promedio ventas diarias total</h2>
                            <x-dynamic-chart type="line" :labels="$ventasDiarias->keys()->toArray()" :data="$ventasDiarias->values()->toArray()" class="w-full h-full"
                                id="venta_diaria" titulo="Ventas Diarias" />
                        </div>

                        <div class="w-1/2 px-2 mt-4">
                            <h2 class="text-center text-xl font-bold mb-4">Categoria con mas ventas</h2>
                            <x-dynamic-chart type="bar" :labels="$ventasPorCategoria->keys()->toArray()" :data="$ventasPorCategoria->values()->toArray()" class="w-full h-full mt-2"
                                id="ventasPorCategoria" titulo="Ventas por Categoría" />
                        </div>
                        <div class="w-1/2 px-2 mt-4">
                            <h2 class="text-center text-xl font-bold mb-4">Productos mas vendidos</h2>
                            <x-dynamic-chart type="bar" :labels="$productosMasVendidos->keys()->toArray()" :data="$productosMasVendidos->values()->toArray()" class="w-full h-full mt-2"
                                id="productosMasVendidos" titulo="Ventas por Producto" />
                        </div>
                        <div class="w-1/2 px-2 mt-4">
                            <h2 class="text-center text-xl font-bold mb-4">Usuarios registrados este mes</h2>
                            <x-dynamic-chart type="line" :labels="$nuevosUsuariosPorMes->keys()->toArray()" :data="$nuevosUsuariosPorMes->values()->toArray()" class="w-full h-64 mt-2"
                                id="nuevosUsuarios" titulo="Usuarios Registrados por Mes" />
                        </div>



                        <div class="w-1/2 px-2 mt-4">
                            <h2 class="text-center text-xl font-bold mb-4">Promedio Mensual de Ventas por Valor</h2>
                            <x-dynamic-chart type="line" :labels="$promedioMensual->keys()->toArray()" :data="$promedioMensual->values()->toArray()" class="w-full h-64 mt-2"
                                id="promedioValorVentaMensual" titulo="Promedio Mensual de Ventas" />
                        </div>

                        <div class="w-1/2 px-2 mt-4">
                            <div>
                                <h2 class="text-center text-xl font-bold mb-4">Metodos de pago mas utilizados</h2>
                                <x-dynamic-chart type="pie" :labels="$metodosPago->pluck('metodo')->toArray()" :data="$metodosPago->pluck('cantidad')->toArray()"
                                    class="w-full h-64 mt-2" id="metodosPago" titulo="Distribución de Métodos de Pago" />
                            </div>


                        </div>


                    </div>
                </div>
            @endrole


            <hr class="my-12 h-0.5 border-t-0 bg-neutral-100 dark:bg-white/10" />

            @role('vendedor')
                <div class="w-full mt-4">
                    <h2 class="text-center text-xl font-bold mb-4">Estadísticas vendedor</h2>
                    <div class="flex flex-wrap -mx-2 ">
                        <div class="w-1/2 px-2 mt-4">
                            <h2 class="text-center text-xl font-bold mb-4">Mis Productos más Vendidos</h2>
                            <x-dynamic-chart type="bar" :labels="$VentasVendedor->keys()->toArray()" :data="$VentasVendedor->values()->toArray()" class="w-full h-64 mt-2"
                                id="VentasVendedor" titulo="Productos Más Vendidos" />
                        </div>

                        <div class="w-1/2 px-2 mt-4">
                            <h2 class="text-center text-xl font-bold mb-4">Progreso de Ventas Mensuales</h2>
                            <x-dynamic-chart type="bar" :labels="$productos->pluck('nombre')->toArray()" :data="$productos->pluck('cantidad')->toArray()" class="w-full h-64 mt-2"
                                id="inventarioProductos" titulo="Inventario general" />
                        </div>

                        <div class="w-1/2 px-2 mt-4">
                            <h2 class="text-center text-xl font-bold mb-4">Calificación Promedio de mis Productos</h2>

                            <x-dynamic-chart type="bar" :labels="$calificacionesProductos->keys()->toArray()" :data="$calificacionesProductos->values()->toArray()" class="w-full h-64 mt-2"
                                id="calificacionesProductos" titulo="Calificación Promedio de los Productos" />
                        </div>
                        <div class="w-1/2 px-2 mt-4">
                            <h2 class="text-center text-xl font-bold mb-4">Progreso de Ventas Diarias</h2>
                            <x-dynamic-chart type="line" :labels="$VentasxDiaVendedor->pluck('fecha')->toArray()" :data="$VentasxDiaVendedor->pluck('cantidad_ventas')->toArray()" class="w-full h-64 mt-2"
                                id="ventasDiarias" titulo="Ventas Diarias" />
                        </div>
                        <div class="w-1/2 px-2 mt-4">
                            <h2 class="text-center text-xl font-bold mb-4">Progreso de Ventas Mensuales</h2>
                            <x-dynamic-chart type="line" :labels="$VentasxMesVendedor->pluck('mes')->toArray()" :data="$VentasxMesVendedor->pluck('cantidad_ventas')->toArray()" class="w-full h-64 mt-2"
                                id="VentasxMesVendedor" titulo="Ventas Mensuales" />
                        </div>


                    </div>
                </div>
            @endrole


        </div>
    </div>
    @include('components.alert-component')

</div>

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush
