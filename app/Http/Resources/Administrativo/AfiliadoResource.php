<?php

namespace App\Http\Resources\Administrativo;

use Illuminate\Http\Resources\Json\JsonResource;

class AfiliadoResource extends JsonResource
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
            'id' => $this->afiliado->id,
            'user_id' => $this->id,
            'codigo_afiliado' => $this->afiliado->codigo_afiliado,
            'nombre' => $this->name,
            'apellido' => $this->lastname,
            'dni' => $this->dni,
            'paquete' => $this->afiliado->paquete->nombre,
            'solicitante' => $this->afiliado->solicitante,
            'activo' => $this->afiliado->activo,
        ];
    }
}
