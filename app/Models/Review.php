<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\UuidTrait;
class Review extends Model
{
    use HasFactory;
    use UuidTrait;

    protected $fillable = [
        'vendedor_id',
        'cliente_id',
        'producto_id',
        'venta_id',
        'comentario',
        'estrellas',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }


}
