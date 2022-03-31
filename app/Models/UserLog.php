<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    use HasFactory;

    protected $fillable = ['user','accion','recurso','ruta'];
    public const CREAR = 'CREAR';
    public const EDITAR = 'EDITAR';
    public const ELIMINAR = 'ELIMINAR';

}
