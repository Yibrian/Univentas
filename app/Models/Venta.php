<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\UuidTrait;

class Venta extends Model
{
    use HasFactory;
    use UuidTrait;
    protected $fillable = ['producto_id', 'vendedor_id', 'cliente_id', 'confirmacion_vendedor', 'confirmacion_cliente'];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
