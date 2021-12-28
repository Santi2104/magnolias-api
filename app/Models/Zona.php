<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Zona
 *
 * @property int $id
 * @property string $nombre
 * @property int $localidad_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Localidad $localidad
 * @method static \Database\Factories\ZonaFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Zona newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Zona newQuery()
 * @method static \Illuminate\Database\Query\Builder|Zona onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Zona query()
 * @method static \Illuminate\Database\Eloquent\Builder|Zona whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zona whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zona whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zona whereLocalidadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zona whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zona whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Zona withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Zona withoutTrashed()
 * @mixin \Eloquent
 */
class Zona extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nombre','localidad_id'];

    /**
     * Get the localidad that owns the Zona
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function localidad()
    {
        return $this->belongsTo(Localidad::class);
    }

    /**
     * The vendedores that belong to the Zona
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vendedores()
    {
        return $this->belongsToMany(Vendedor::class, 'vendedor_zona');
    }
}
