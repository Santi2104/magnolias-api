<?php

namespace App\Http\Resources\Administrativo;

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
            'id' => $this->vendedor->id,
            'user_id' => $this->id,
            'nombre' => $this->name,
            'apellido' => $this->lastname,
            'dni' => $this->dni,
            "edad" => $this->edad,
            "nacimiento" => $this->nacimiento,
            'email' => $this->email,
            'codigo_vendedor' => $this->vendedor->codigo_vendedor,
            'coordinador' => $this->vendedor->coordinador->user->name ." ".$this->vendedor->coordinador->user->lastname
        ];
    }
}
