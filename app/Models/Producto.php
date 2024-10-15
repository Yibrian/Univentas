<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\UuidTrait;

class Producto extends Model
{
    use HasFactory;
    use UuidTrait;

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'vendedor_id',
        'categoria_id',
        'disponibilidad',
        'cantidad',
        'imagen'
    ];

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }


    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
