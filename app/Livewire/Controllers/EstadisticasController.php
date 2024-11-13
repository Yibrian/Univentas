<?php

namespace App\Livewire\Controllers;

use Auth;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\Venta;
use App\Models\User;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Vendedor;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;


class EstadisticasController extends Component
{
    public $ventas;
    public $ventas_tiempo;
    public $ventasPorCategoria;
    public $productosMasVendidos;
    public $nuevosUsuariosPorMes;
    public $ventasDiarias;
    public $ventasSemanales;
    public $ventasMensuales;
    public $promedioMensual;
    public $metodosPago;

    public $VentasVendedor;

    public $productos;

    public $calificacionesProductos;

    public $VentasxDiaVendedor;
    public $VentasxMesVendedor;


    public function mount()
    {
        $ventasPorMes = Venta::selectRaw('MONTH(created_at) as mes, COUNT(*) as cantidad')->groupBy('mes')->orderBy('mes')->pluck('cantidad', 'mes');

        $meses = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',];

        $this->ventas_tiempo = $ventasPorMes->mapWithKeys(function ($cantidad, $mes) use ($meses) {
            return [$meses[$mes] => $cantidad];
        });

        //ADMIN STATS

        $this->ventasPorCategoria = Venta::join('productos', 'ventas.producto_id', '=', 'productos.id')->join('categorias', 'productos.categoria_id', '=', 'categorias.id')->selectRaw('categorias.nombre as categoria, COUNT(*) as cantidad')->groupBy('categorias.nombre')->pluck('cantidad', 'categoria');
        $this->productosMasVendidos = Venta::join('productos', 'ventas.producto_id', '=', 'productos.id')->selectRaw('productos.nombre as producto, COUNT(ventas.id) as cantidad')->groupBy('productos.nombre')->orderByDesc('cantidad')->limit(10)->pluck('cantidad', 'producto');

        $this->nuevosUsuariosPorMes = User::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mes"), DB::raw('COUNT(*) as cantidad'))->groupBy('mes')->orderBy('mes')->pluck('cantidad', 'mes');

        $this->ventasDiarias = Venta::select(DB::raw("DATE(created_at) as fecha"), DB::raw('COUNT(*) as cantidad'))->groupBy('fecha')->orderBy('fecha')->pluck('cantidad', 'fecha');

        $this->ventasSemanales = Venta::select(DB::raw("YEARWEEK(created_at) as semana"), DB::raw('COUNT(*) as cantidad'))->groupBy('semana')->orderBy('semana')->pluck('cantidad', 'semana');

        $this->ventasMensuales = Venta::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mes"), DB::raw('COUNT(*) as cantidad'))->groupBy('mes')->orderBy('mes')->pluck('cantidad', 'mes');

        $this->promedioMensual = Venta::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mes"), DB::raw("AVG(valor) as promedio"))->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))->orderBy('mes')->pluck('promedio', 'mes');

        $this->metodosPago = Venta::select('metodo', DB::raw('count(*) as cantidad'))->groupBy('metodo')->get();


        //VENDEDOR STATS

        $vendedorId = Auth::user()->vendedor->id;

        $productosMasVendidosVendedor = Venta::select('producto_id', DB::raw('count(*) as cantidad_ventas'))
            ->where('vendedor_id', $vendedorId)
            ->groupBy('producto_id')
            ->orderByDesc('cantidad_ventas')
            ->limit(10)
            ->get();

        $productos = Producto::whereIn('id', $productosMasVendidosVendedor->pluck('producto_id'))
            ->get()
            ->keyBy('id');

        $this->VentasVendedor = $productosMasVendidosVendedor->mapWithKeys(function ($venta) use ($productos) {
            $nombreProducto = $productos[$venta->producto_id]->nombre;
            return [$nombreProducto => $venta->cantidad_ventas];
        });


        $vendedor = Vendedor::find($vendedorId);
        $inventarioProductos = $vendedor->productos;
        $this->productos = $inventarioProductos->map(function ($producto) {
            return [
                'nombre' => $producto->nombre,
                'cantidad' => $producto->cantidad
            ];
        });

        $this->calificacionesProductos = $vendedor->productos()
            ->with('reviews')
            ->get()
            ->mapWithKeys(function ($producto) {
                $promedioCalificacion = $producto->reviews()->avg('estrellas'); // Calcula el promedio
                return [$producto->nombre => $promedioCalificacion];
            });

        $this->VentasxDiaVendedor =$vendedor->ventas()
        ->select(
            DB::raw('DATE(created_at) as fecha'),
            DB::raw('count(*) as cantidad_ventas')
        )
        ->groupBy('fecha')
        ->orderBy('fecha')
        ->get();

        $this->VentasxMesVendedor = $vendedor->ventas()
        ->select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mes'), // Agrupamos por mes y año
            DB::raw('count(*) as cantidad_ventas')
        )
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();



    }
    public function render()
    {
        return view('livewire.ventas.estadisticas')->layout('layouts.app');
    }
}
