<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\UuidTrait;

class Categoria extends Model
{
    use HasFactory;
    use UuidTrait;

    protected $fillable = [
        'nombre',
        'photo',
    ];

    public function productos(){
        return $this->hasMany(Producto::class);

    }

}
