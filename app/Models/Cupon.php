<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\UuidTrait;

class Cupon extends Model
{
    use HasFactory;
    use UuidTrait;
    protected $table = 'cupones';


    protected $fillable = [
        'codigo',
        'descuento',
        'tipo',
        'usos',
        'fecha_expiracion',
        'vendedor_id'
    ];

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

}
