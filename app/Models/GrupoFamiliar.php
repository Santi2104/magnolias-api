<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoFamiliar extends Model
{
    use HasFactory;

    protected $table = 'grupo_familiar';

    protected $fillable = ['apellido','dni_solicitante'];

    /**
     * Get the afiliado associated with the GrupoFamiliar
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function afiliado()
    {
        return $this->hasOne(Afiliado::class);
    }
}
