<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\UuidTrait;

class Cliente extends Model
{
    use HasFactory;
    use UuidTrait;

    protected $fillable = [
        'user_id',
        'telefono',
        'direccion',
        'genero',
        'fecha_nac',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function compras(){
        return $this->hasMany(related: Venta::class);

    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


}
