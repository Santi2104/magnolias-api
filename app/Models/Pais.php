<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    use HasFactory;
    protected $table = 'paises';
    protected $fillable = ['npais', 'activo'];

    /**
     * Get all of the provincias for the Pais
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function provincias()
    {
        return $this->hasMany(Provincia::class);
    }
}
