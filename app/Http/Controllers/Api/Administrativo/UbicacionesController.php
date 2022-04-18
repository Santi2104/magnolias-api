<?php

namespace App\Http\Controllers\Api\Administrativo;

use App\Models\Pais;
use App\Models\Localidad;
use App\Models\Provincia;
use App\Models\Departamento;
use Illuminate\Http\Request;
use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;

class UbicacionesController extends Controller
{
    use ApiHelpers;
    /**
     * Lista las provincias de un pais determinado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function listarProvinciasPorPais($id)
    {
        $pais = Pais::with([
            'provincias' => function($query){
                $query->select('id','pais_id','nprovincia AS nombre');
            }
        ])
        ->where('id', $id)
        ->get(['id','npais AS nombre']);

        if(!isset($pais))
        {
            return $this->onError(404,"Error al encontrar el recurso","El id enviado no pertenece a ningun recurso");
        }

        return $this->onSuccess($pais);
    }

    /**
     * Lista los departamentos de una provincia determinada.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function listarDepartamentosPorProvincia($id)
    {
        $provincia = Provincia::with([
            'departamentos' => function($query){
                $query->select('id','ndepartamento AS nombre','provincia_id');
            }
        ])
        ->where('id',$id)
        ->first(['id','nprovincia AS nombre']);

        if(!isset($provincia)){
            return $this->onError(404,"Recurso no encontrado","El id provisto no pertenece a ningun recurso");
        }

        return $this->onSuccess($provincia,"recurso encontrado");
    }

    /**
     * Lista las localidades de un departamentos determinado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function listarLocalidadesPorDepartamento($id)
    {
        $departamento = Departamento::with([
            'localidades' => function($query){
                $query->select('id','nlocalidad AS nombre','departamento_id');
            }
        ])
        ->where('id',$id)
        ->first(['id','ndepartamento AS nombre']);

        if(!isset($departamento)){
            return $this->onError(404,"Recurso no encontrado","El id provisto no pertenece a ningun recurso");
        }

        return $this->onSuccess($departamento);
    }

    /**
     * Lista las calles de una localidad determinada.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function listarCallesPorLocalidad($id)
    {
        $localidad = Localidad::with([
            'calles' => function($query){
                $query->select('id','ncalle AS nombre','localidad_id');
            },
        ])
        ->where('id',$id)
        ->first(['id','nlocalidad AS nombre']);

        if(!isset($localidad)){
            return $this->onError(404,"Recurso no encontrado","El id provisto no pertenece a ningun recurso");
        }

        return $this->onSuccess($localidad);
    }

    /**
     * Lista los barrios de una localidad determinada.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function listarBarriosPorLocalidad($id)
    {
        $localidad = Localidad::with([
            'barrios' => function($query){
                $query->select('id','nbarrio AS nombre','localidad_id');
            },
        ])
        ->where('id',$id)
        ->first(['id','nlocalidad AS nombre']);

        if(!isset($localidad)){
            return $this->onError(404,"Recurso no encontrado","El id provisto no pertenece a ningun recurso");
        }

        return $this->onSuccess($localidad);
    }
}
