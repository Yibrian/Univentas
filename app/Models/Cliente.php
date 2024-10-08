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

}
