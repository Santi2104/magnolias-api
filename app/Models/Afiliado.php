<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Afiliado
 *
 * @property int $id
 * @property string $codigo_afiliado
 * @property string $calle
 * @property string $barrio
 * @property string $nro_casa
 * @property string|null $nro_depto
 * @property int $user_id
 * @property int $paquete_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $obra_social_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\ObraSocial $obraSocial
 * @property-read \App\Models\Paquete $paquete
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Vendedor[] $vendedores
 * @property-read int|null $vendedores_count
 * @method static \Database\Factories\AfiliadoFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Afiliado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Afiliado newQuery()
 * @method static \Illuminate\Database\Query\Builder|Afiliado onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Afiliado query()
 * @method static \Illuminate\Database\Eloquent\Builder|Afiliado whereBarrio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afiliado whereCalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afiliado whereCodigoAfiliado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afiliado whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afiliado whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afiliado whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afiliado whereNroCasa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afiliado whereNroDepto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afiliado whereObraSocialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afiliado wherePaqueteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afiliado whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afiliado whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Afiliado withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Afiliado withoutTrashed()
 * @mixin \Eloquent
 */
class Afiliado extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "user_id",
        "paquete_id",
        'codigo_afiliado',
        'calle',
        'barrio',
        'nro_casa',
        'nro_depto',
        'obra_social_id',
        'grupo_familiar_id',
        'solicitante',
        'parentesco',
        'sexo',
        'finaliza_en',
        'cuil',
        'estado_civil',
        'profesion_ocupacion',
        'poliza_electronica',
        'nombre_tarjeta',
        'numero_tarjeta',
        'codigo_cvv',
        'tipo_tarjeta',
        'banco',
        'vencimiento_tarjeta',
        'titular_tarjeta',
        'codigo_postal',
        'ultimo_pago'
    ];

    public const parentesco = ['Padre','Madre','Hijo','Hija','Conyugue'];
    public const sexo = ['M','F'];
    public const estado_civil = ['soltero', 'casado', 'viudo', 'divorciado'];

    /**
     * The vendedores that belong to the Afiliado
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vendedores()
    {
        return $this->belongsToMany(Vendedor::class,'afiliado_vendedor');
    }

    /**
     * Get the obraSocial that owns the Afiliado
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function obraSocial()
    {
        return $this->belongsTo(ObraSocial::class);
    }

    /**
     * Get the user that owns the Afiliado
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the paquete that owns the Afiliado
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paquete()
    {
        return $this->belongsTo(Paquete::class);
    }

    /**
     * Get the grupoFamiliar that owns the Afiliado
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grupoFamiliar()
    {
        return $this->belongsTo(GrupoFamiliar::class);
    }

    /**
     * Get all of the pagos for the Afiliado
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
