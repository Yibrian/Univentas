<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;
use App\UuidTrait;


class Role extends SpatieRole
{
    use HasFactory;
    use UuidTrait;
    protected $primaryKey = 'id';
    protected $casts = [
        'id' => 'string'
    ];
}