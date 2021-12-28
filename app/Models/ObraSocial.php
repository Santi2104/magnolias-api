<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ObraSocial
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Afiliado[] $afiliados
 * @property-read int|null $afiliados_count
 * @method static \Database\Factories\ObraSocialFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ObraSocial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ObraSocial newQuery()
 * @method static \Illuminate\Database\Query\Builder|ObraSocial onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ObraSocial query()
 * @method static \Illuminate\Database\Eloquent\Builder|ObraSocial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ObraSocial whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ObraSocial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ObraSocial whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ObraSocial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ObraSocial withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ObraSocial withoutTrashed()
 * @mixin \Eloquent
 */
class ObraSocial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nombre'];
    protected $table = 'obra_sociales';

    /**
     * Get all of the afiliados for the ObraSocial
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function afiliados()
    {
        return $this->hasMany(Afiliado::class);
    }
}
