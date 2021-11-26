<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'lastname',
        'dni',
        'edad',
        'nacimiento',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Get the role that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the coordinador associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function coordinador()
    {
        return $this->hasOne(Coordinador::class, 'user_id');
    }

    /**
     * Get the afiliado associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function afiliado()
    {
        return $this->hasOne(Afiliado::class);
    }
}
