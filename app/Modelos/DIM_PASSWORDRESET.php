<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class DIM_PASSWORDRESET extends Model
{
    //

    protected $table = 'desaerp.dbo.DIM_PASSWORDRESET';

    public $timestamps = false;


    /**
     * Elimina el registro del token de reseteo utilizado.
     *
     * @param $correo
     * @param $token
     */
    static function eliminaReseteo( $correo, $token )
    {

        $resultados = DIM_PASSWORDRESET::where( 'email', '=', $correo )
                        ->where( 'token', '=', $token )
                        ->get();

        if( isset( $resultados[0] ) )
        {
            $resultado = $resultados[0];

            try{
                $resultado->delete();
            }
            catch(\Exception $e){

            }
        }

    }

}
