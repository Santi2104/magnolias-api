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
            'nro_solicitud' => $this->afiliado->nro_solicitud,
            'codigo_afiliado' => $this->afiliado->codigo_afiliado,
            'nombre' => $this->name,
            'apellido' => $this->lastname,
            'dni' => $this->dni,
            'tipo_dni' => $this->tipo_dni,
            "edad" => $this->edad,
            "nacimiento" => $this->nacimiento,
            "email" => $this->email,
            "telefono_particular" => $this->afiliado->telefono_particular,
            "calle" => $this->afiliado->calle,
            "barrio" => $this->afiliado->barrio,
            "provincia" => $this->afiliado->provincia,
            "localidad" => $this->afiliado->localidad,
            "nro_casa" => $this->afiliado->nro_casa,
            "departamento" => $this->afiliado->departamento,
            "nacimiento" => $this->nacimiento,
            'paquete' => $this->afiliado->paquete ? $this->afiliado->paquete->nombre : '',
            'solicitante' => $this->afiliado->solicitante,
            'activo' => $this->afiliado->activo ? 'Activo' : 'No activo',
            'fecha_alta' => $this->created_at->format('Y-m-d'),
            'finaliza_en' => $this->afiliado->finaliza_en,
            'created_at' => $this->afiliado->created_at,
            'vendedor' => $this->afiliado->vendedores,
        ];
    }
}
