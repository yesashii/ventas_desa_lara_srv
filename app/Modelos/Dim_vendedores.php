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


}
