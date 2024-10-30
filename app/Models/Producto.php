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
        'imagen',
        'envio_domicilio',
        'precio_domicilio'
    ];

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }


    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function ventas(){
        return $this->hasMany(Venta::class);

    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

}
