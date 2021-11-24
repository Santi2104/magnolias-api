<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    use HasFactory;

    protected $table = 'vendedores';
    protected $fillable = ['user_id', 'zona_id','coordinador_id','codigo_vendedor'];

    /**
     * The afiliados that belong to the Vendedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function afiliados()
    {
        return $this->belongsToMany(Afiliado::class, 'afiliado_vendedor');
    }

    /**
     * Get the zona that owns the Vendedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function zona()
    {
        return $this->belongsTo(Zona::class);
    }

    /**
     * Get the coordinador that owns the Vendedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coordinador()
    {
        return $this->belongsTo(Coordinador::class);
    }

    /**
     * Get the user that owns the Vendedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
