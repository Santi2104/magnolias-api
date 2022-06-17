<?php

namespace App\Http\Resources\Administrativo;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowAfiliadoResource extends JsonResource
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
            "calle" => $this->calle,
            "barrio" => $this->barrio,
            "nro_casa" => $this->nro_casa,
            "nro_depto" => $this->nro_depto,
            "solicitante" => $this->solicitante,
            "sexo" => $this->sexo,
            "parentesco" => $this->parentesco,
            "CUIL" => $this->cuil,
            "lugar_nacimiento" => $this->lugar_nacimiento,
            "domicilio" => $this->domicilio,
            "localidad" => $this->localidad,
            "provincia" => $this->provincia,
            "departamento" => $this->departamento,
            "codigo_postal" => $this->codigo_postal,
            "estado_civil" => $this->estado_civil,
            "telefono_particular" => $this->telefono_particular,
            "profesion_ocupacion" => $this->profesion_ocupacion,
            "poliza_electronica" => $this->poliza_electronica,
            "trabajo" => $this->trabajo,
            "domicilio_laboral" => $this->domicilio_laboral,
            "localidad_laboral" => $this->localidad_laboral,
            "provincia_laboral" => $this->provincia_laboral,
            "codigo_postal_laboral" => $this->codigo_postal_laboral,
            "email_laboral" => $this->email_laboral,
            "telefono_laboral" => $this->telefono_laboral,
            "seguro_retiro" => $this->seguro_retiro,
            "user_id" => $this->user_id,
            'nombre' => $this->user->name,
            'apellido' => $this->user->lastname,
            'dni' => $this->user->dni,
            'nacimiento' => $this->user->nacimiento,
            'edad' => $this->user->edad,
            "paquete_id" => $this->paquete->nombre,
            "fecha_carga" => $this->created_at,
            "fecha_actualizacion" => $this->updated_at,
            'finaliza_en' => $this->finaliza_en,
            "obra_social_id" => $this->obraSocial->nombre,
            "grupo_familiar_id" => 1
        ];
    }
}
