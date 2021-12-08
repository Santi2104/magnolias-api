<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
