<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCoordinadorResource extends JsonResource
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
            "nombre" => $this->name,
            "apellido" => $this->lastname,
            "dni" => $this->dni,
            "edad" => $this->edad,
            "nacimiento" => $this->nacimiento,
            "email" => $this->email,
            "role_name" => $this->role->name,
            "coordinador" => $this->coordinador
        ];
    }
}
