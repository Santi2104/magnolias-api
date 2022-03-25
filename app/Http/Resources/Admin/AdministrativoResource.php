<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdministrativoResource extends JsonResource
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
            "codigo_administrativo" => $this->codigo_administrativo,
            "user_id" => $this->user_id,
            "user" => $this->user,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
