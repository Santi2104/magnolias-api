<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Localidad extends Model
{
    use HasFactory, SoftDeletes;

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
