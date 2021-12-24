<?php

namespace App\Http\Resources\Vendedor;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowAfiliadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "codigo_afiliado" => $this->codigo_afiliado,
            "calle" => $this->calle,
            "barrio" => $this->barrio,
            "nro_casa" => $this->nro_casa,
            "nro_depto" => $this->nro_depto,
            "user_id" => $this->user_id,
            "user" => $this->user,
            "paquete_id" => $this->paquete_id,
            "paquete" => $this->paquete,
            "obra_social_id" => $this->obra_social_id,
            "obra_social" => $this->obraSocial,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
