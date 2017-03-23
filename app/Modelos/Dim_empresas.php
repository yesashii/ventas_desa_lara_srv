<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

// use App\Modelos\Dim_empresas

class Dim_empresas extends Model
{
    //
    protected $table        = "desaerp.dbo.DIM_EMPRESAS";

    protected $primaryKey   = 'idempresa';


    /**
     * Trae las tres empresas que usa el sistema de ventas
     *
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
   public function scopetraeEmpresas()
   {
       return static::find([1,3,4]) ;
   }






}
