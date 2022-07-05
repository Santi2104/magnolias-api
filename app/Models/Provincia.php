<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    use HasFactory;
    protected $fillable = ['nprovincia','activo', 'pais_id'];

    /**
     * Get the pais that owns the Provincia
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pais()
    {
        return $this->belongsTo(Pais::class);
    }

    /**
     * Get all of the departamentos for the Provincia
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function departamentos()
    {
        return $this->hasMany(Departamento::class);
    }
}
