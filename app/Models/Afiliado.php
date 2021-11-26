<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Afiliado extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "paquete_id",
        'codigo_afiliado',
        'calle',
        'barrio',
        'nro_casa',
        'nro_depto'
    ];

    /**
     * The vendedores that belong to the Afiliado
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vendedores()
    {
        return $this->belongsToMany(Vendedor::class,'afiliado_vendedor');
    }

    /**
     * Get the obraSocial that owns the Afiliado
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function obraSocial()
    {
        return $this->belongsTo(ObraSocial::class);
    }
}
