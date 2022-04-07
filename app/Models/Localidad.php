<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Localidad
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Zona[] $zonas
 * @property-read int|null $zonas_count
 * @method static \Database\Factories\LocalidadFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Localidad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Localidad newQuery()
 * @method static \Illuminate\Database\Query\Builder|Localidad onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Localidad query()
 * @method static \Illuminate\Database\Eloquent\Builder|Localidad whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Localidad whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Localidad whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Localidad whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Localidad whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Localidad withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Localidad withoutTrashed()
 * @mixin \Eloquent
 */
class Localidad extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nlocalidad','departamento_id','codigo_postal'];
    protected $table = 'localidades';

    /**
     * The vendedores that belong to the Localidad
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vendedores()
    {
        return $this->belongsToMany(Vendedor::class);
    }
}
