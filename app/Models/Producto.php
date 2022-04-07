<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Producto
 *
 * @property int $id
 * @property string $nombre
 * @property int $categoria_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Categoria $categoria
 * @property-read \Illuminate\Database\Eloquent\Collection|Producto[] $paquetes
 * @property-read int|null $paquetes_count
 * @method static \Database\Factories\ProductoFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Producto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Producto newQuery()
 * @method static \Illuminate\Database\Query\Builder|Producto onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Producto query()
 * @method static \Illuminate\Database\Eloquent\Builder|Producto whereCategoriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Producto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Producto whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Producto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Producto whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Producto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Producto withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Producto withoutTrashed()
 * @mixin \Eloquent
 */
class Producto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nombre'];


    /**
     * The productos that belong to the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function paquetes()
    {
        return $this->belongsToMany(Producto::class,'paquete_producto');
    }
}
