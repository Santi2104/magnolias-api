<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Paquete
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Producto[] $productos
 * @property-read int|null $productos_count
 * @method static \Database\Factories\PaqueteFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Paquete newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Paquete newQuery()
 * @method static \Illuminate\Database\Query\Builder|Paquete onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Paquete query()
 * @method static \Illuminate\Database\Eloquent\Builder|Paquete whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paquete whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paquete whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paquete whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paquete whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Paquete withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Paquete withoutTrashed()
 * @mixin \Eloquent
 */
class Paquete extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nombre', 'precio', 'descripcion'];

    /**
     * The productos that belong to the Paquete
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function productos()
    {
        return $this->belongsToMany(Producto::class,'paquete_producto')->withTimestamps();
    }


}
