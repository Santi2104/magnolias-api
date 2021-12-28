<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Suscripcion
 *
 * @method static \Database\Factories\SuscripcionFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Suscripcion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Suscripcion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Suscripcion query()
 * @mixin \Eloquent
 */
class Suscripcion extends Model
{
    use HasFactory;

    protected $table = 'suscripciones';
}
