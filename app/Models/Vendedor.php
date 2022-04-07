<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Vendedor
 *
 * @property int $id
 * @property int $user_id
 * @property string $codigo_vendedor
 * @property int $zona_id
 * @property int $coordinador_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Afiliado[] $afiliados
 * @property-read int|null $afiliados_count
 * @property-read \App\Models\Coordinador $coordinador
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Zona $zona
 * @method static \Database\Factories\VendedorFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Vendedor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vendedor newQuery()
 * @method static \Illuminate\Database\Query\Builder|Vendedor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Vendedor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vendedor whereCodigoVendedor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vendedor whereCoordinadorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vendedor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vendedor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vendedor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vendedor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vendedor whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vendedor whereZonaId($value)
 * @method static \Illuminate\Database\Query\Builder|Vendedor withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Vendedor withoutTrashed()
 * @mixin \Eloquent
 */
class Vendedor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vendedores';
    protected $fillable = ['user_id', 'zona_id','coordinador_id','codigo_vendedor'];

    /**
     * The afiliados that belong to the Vendedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function afiliados()
    {
        return $this->belongsToMany(Afiliado::class, 'afiliado_vendedor');
    }

    /**
     * The localidades that belong to the Vendedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function localidades()
    {
        return $this->belongsToMany(Localidad::class,'vendedores_localidades');
    }

    /**
     * Get the coordinador that owns the Vendedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coordinador()
    {
        return $this->belongsTo(Coordinador::class);
    }

    /**
     * Get the user that owns the Vendedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
