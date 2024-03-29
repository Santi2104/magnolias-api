<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class VendedoresResource extends JsonResource
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
            'id' => $this->vendedor->id,
            'user_id' => $this->id,
            'nombre' => $this->name,
            'apellido' => $this->lastname,
            'dni' => $this->dni,
            'email' => $this->email,
            "edad" => $this->edad,
            "nacimiento" => $this->nacimiento,
            'codigo_vendedor' => $this->vendedor->codigo_vendedor
        ];
    }
}
