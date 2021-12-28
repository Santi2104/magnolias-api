<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Categoria
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Producto[] $productos
 * @property-read int|null $productos_count
 * @method static \Database\Factories\CategoriaFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria newQuery()
 * @method static \Illuminate\Database\Query\Builder|Categoria onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria query()
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Categoria withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Categoria withoutTrashed()
 * @mixin \Eloquent
 */
class Categoria extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["nombre"];

    /**
     * Get all of the productos for the Categoria
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productos()
    {
        return $this->hasMany(Producto::class,'categoria_id');
    }
}
