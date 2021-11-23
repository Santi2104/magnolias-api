<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];
    protected $table = 'localidades';

    /**
     * Get all of the zonas for the Localidad
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function zonas()
    {
        return $this->hasMany(Zona::class);
    }
}
