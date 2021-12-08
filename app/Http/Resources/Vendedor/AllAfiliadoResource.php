<?php

namespace App\Http\Resources\Vendedor;

use Illuminate\Http\Resources\Json\JsonResource;

class AllAfiliadoResource extends JsonResource
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
            "user_id" => $this->user_id,
            "user" => $this->user,
            "paquete" => $this->paquete,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
