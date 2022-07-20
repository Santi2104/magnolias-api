<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Banco extends Model
{
    use HasFactory;

    protected $fillable = ['codigo','nombre'];

    public function setNombreAttribute($value)
    {
        $this->attributes["nombre"] = strtoupper($value);
    }


    public function getNombreAttribute($value)
    {
        return strtoupper($value);
    }
}
