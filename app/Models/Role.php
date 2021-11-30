<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public const ES_ADMIN = 1;
    public const ES_COORDINADOR = 2;
    public const ES_AFILIADO = 3;
    public const ES_VENDEDOR = 4;
    public const ADMIN_TOKEN = ['admin'];
    public const COORDINADOR_TOKEN = ["coordinador"];
    public const AFILIADO_TOKEN = ["afiliado"];
    public const VENDEDOR_TOKEN = ["vendedor"];

    /**
     * Get all of the users for the Role
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
