<?php

namespace App\Http\Resources\Administrativo;

use Illuminate\Http\Resources\Json\JsonResource;

class CoordinadorResource extends JsonResource
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
            'id' => $this->coordinador->id,
            'user_id' => $this->id,
            'nombre' => $this->name,
            'apellido' => $this->lastname,
            'dni' => $this->dni,
            "edad" => $this->edad,
            "nacimiento" => $this->nacimiento,
            'email' => $this->email,
            'codigo_coordinador' => $this->coordinador->codigo_coordinador
        ];
    }
}
