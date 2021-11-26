<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObraSocial extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];
    protected $table = 'obra_sociales';

    /**
     * Get all of the afiliados for the ObraSocial
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function afiliados()
    {
        return $this->hasMany(Afiliado::class);
    }
}
