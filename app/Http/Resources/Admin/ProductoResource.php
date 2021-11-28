<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
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
            "nombre" => $this->nombre,
            "categoria_id" => $this->categoria_id,
            "categoria" => $this->categoria,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
