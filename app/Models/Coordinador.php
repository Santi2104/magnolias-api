<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Coordinador
 *
 * @property int $id
 * @property string $codigo_coordinador
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Vendedor[] $vendedores
 * @property-read int|null $vendedores_count
 * @method static \Database\Factories\CoordinadorFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinador newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinador newQuery()
 * @method static \Illuminate\Database\Query\Builder|Coordinador onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinador query()
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinador whereCodigoCoordinador($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinador whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinador whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinador whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinador whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinador whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Coordinador withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Coordinador withoutTrashed()
 * @mixin \Eloquent
 */
class Coordinador extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'coordinadores';
    protected $fillable = ['user_id','codigo_coordinador'];

    /**
     * Get the user that owns the Coordinador
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the vendedores for the Coordinador
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vendedores()
    {
        return $this->hasMany(Vendedor::class,'coordinador_id');
    }
}
