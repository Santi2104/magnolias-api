<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Afiliado extends Model
{
    use HasFactory;

    protected $fillable = ["user_id","paquete_id"];

    /**
     * The vendedores that belong to the Afiliado
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vendedores()
    {
        return $this->belongsToMany(Vendedor::class,'afiliado_vendedor');
    }
}
