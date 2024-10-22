<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\UuidTrait;

class Vendedor extends Model
{
    use HasFactory;

    use UuidTrait;

    public $table = 'vendedores';

    protected $fillable = [
        'user_id',
        'nombre_tienda',
        'descripcion',
        'foto_tienda',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productos(){
        return $this->hasMany(Producto::class);

    }

    public function ventas(){
        return $this->hasMany(Venta::class);

    }


}
