<?php

namespace App\Http\Resources\Coordinador;

use Illuminate\Http\Resources\Json\JsonResource;

class VendedorResource extends JsonResource
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
            "id_vendedor" => $this->id,
            "codigo_vendedor" => $this->codigo_vendedor,
            "user_id" => $this->user_id,
            "usuario" => $this->user,
            "zona_id" => $this->zona_id,
            "zona" => $this->zona,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at

        ];
    }
}
