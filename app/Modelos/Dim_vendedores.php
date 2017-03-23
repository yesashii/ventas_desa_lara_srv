<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dim_vendedores extends Model
{
    //

    protected $table        = "desaerp.dbo.dim_vendedores2";

    protected $primaryKey   = "idvendedor";

    public $timestamps      = false;




    /**
     * Trae un vendedor
     *
     *
     * @param $email
     * @param $password
     * @param $idempresa
     * @return array
     */
    static function traeVendedorReset( $email, $idempresa )
    {
        $vendedor = [];

        $vendedores =  Dim_vendedores::where('email', '=', $email)
                        ->Where('idempresa', '=', $idempresa)
                        ->get();

        if ( isset( $vendedores[0] ) )
        {
            $vendedor = $vendedores[0];
        }

        return $vendedor;
    }



    /**
     * verifica si el correo existe y si esta vigente
     *
     * @param $mail
     * @return bool
     */
    public static function verificaMail($mail)
    {

        $resultado = static::where('email', '=', $mail )
            ->where('sw_vigente', '=', 'S' )
            ->where('email', '<>', '' )
            ->get();

        if( isset( $resultado[0] ) )
        {
            return true;
        }else{

            return false;
        }

    }


    /**
     * Verifica si existe el vendedor con las credenciales entregadas
     *
     *
     * @param $email
     * @param $idempresa
     * @param $psw_pedidos
     * @return bool
     */
    public static function existeVendedor( $email, $idempresa, $psw_pedidos )
    {
        $vendedores     = static::where('email', $email)
                        ->Where( 'idempresa', $idempresa)
                        ->Where( 'psw_pedidos', $psw_pedidos)
                        ->get();

        if( isset( $vendedores[0] ) )
        {
            return true;
        }else{

            return false;
        }
    }

    public static function traeVendedor( $email, $idempresa )
    {
        $vendedor = [];

        $vendedores =  static::where('email', '=', $email)
            ->Where('idempresa', '=', $idempresa)
            ->get();

        if ( isset( $vendedores[0] ) )
        {
            $vendedor = $vendedores[0];
        }

        return $vendedor;
    }



}
