<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarjeta extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public function setNombreAttribute($value)
    {
        $this->attributes["nombre"] = strtoupper($value);
    }


    public function getNombreAttribute($value)
    {
        return strtoupper($value);
    }
}
