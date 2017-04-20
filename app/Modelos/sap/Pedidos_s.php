<?php

namespace App\Modelos\sap;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

use PDO;

class Pedidos_s extends Model
{

    /**Conexion por odbc PDO Hana local,
     *
     *
     * @return PDO
     */
    public static function makePdoHana()
    {

        $SERVERNODE     = env('SAP_SERVERNODE');
        $username	    = env('SAP_UIDE');
        $password	    = env('SAP_PWD');

        try{

            $pdo    = new PDO( "odbc:".$SERVERNODE, $username, $password );


        } catch ( PDOException $e ){

            die( $e->getMessage() );

        }

        return $pdo;
    }

    public static function makeOdbcHana()
    {
        $driver 	    = "HDBODBC32";          // 32 bit odbc drivers that come with the hana client installation.
        $servername     = "10.0.0.13:30015";    //"yourservername.vm.cld.sr:30015"; // Enter your external access server name
        $db_name	    = "HIS_DESA";           // This is the default name of your hana instance.
        $username	    = "SYSTEM";             // This is the default username, do provide your username
        $password	    = "Passw0rd";           // This is the default password, do provide your own password.
        $conn   	    = odbc_connect("Driver=$driver;ServerNode=$servername;Database=$db_name;", $username, $password, SQL_CUR_USE_ODBC);

        if (!($conn)) {
            echo "<p>Connection to DB via ODBC failed: ";
            echo odbc_errormsg ($conn );
            echo "</p>\n";
        }
        else{
            echo "conexion exitosa";
        }
        $dbh = new PDO($conn);
    }


    public static function prueba()
    {

        $pdo = self::makePdoHana();

        $consulta = $pdo->prepare("SELECT NOTAPEDIDO FROM HIS_DESA.DESA_DOCENC WHERE ((CORRELATIVO = 1089499 ) AND TIPODOCUMENTO= 'FACT. AFECTA ELEC' )");

        $consulta->execute();

        $notaspedido = $consulta->fetchAll(PDO::FETCH_OBJ);

        dd($notaspedido);

    }


    public static function traeEncabezadoDocumentoSapDesa( $docnum )
    {
        $pdo = self::makePdoHana();

        $sql = "".
            "SELECT vigencia,                                        ".
            "       FLETE,                                           ".
            "       AFECTO,                                          ".
            "       IMPUESTO,                                        ".
            "       total,                                           ".
            "       correlativo,                                     ".
            "       NOTAPEDIDO,                                      ".
            "       razonsocial,                                     ".
            "       ctacte,                                          ".
            "       sigla,                                           ".
            "       fechaemision,                                    ".
            "       direcciondespacho,                               ".
            "       comuna,                                          ".
            "       condicionpago,                                   ".
            "       vendedorcliente,                                 ".
            "       vendedorfactura,                                 ".
            "       sucursal,                                        ".
            "       bodega,                                          ".
            "       referencia,                                      ".
            "       fechaoc,                                         ".
            "       empresa,                                         ".
            "       RIGHT('00000'                                    ".
            "             || Cast(docnum AS VARCHAR), 10) AS NUMERO, ".
            "       tipodocumento                                    ".
            "FROM   HIS_DESA.desa_docenc                             ".
            "WHERE  empresa = 'DESA'                                 ".
            "       AND docnum = '{$docnum}'                         ".
            "       AND tipodocumento = 'FACT. AFECTA ELEC'          ";

       // dd( $sql );

        $consulta = $pdo->prepare( $sql );

        $consulta->execute();

        $desa_docenc = $consulta->fetchAll(PDO::FETCH_OBJ);


        foreach ( $desa_docenc as $desa_enc)
        {
            $r_desa_enc = $desa_enc;
        }
        return $r_desa_enc;
    }


    public static function traeGrillaFacturaSap( $correlativo )
    {

        $pdo = self::makePdoHana();

        $sql = "".
        "SELECT producto,                                   ".
        "       descripcin AS glosa,                        ".
        "       cantidad,                                   ".
        "       precio,                                     ".
        "       neto,                                       ".
        "       descuento                                   ".
        "FROM   HIS_DESA.desa_docdet                        ".
        "WHERE  ( empresa = 'DESA' )                        ".
        "       AND ( tipodocumento = 'FACT. AFECTA ELEC' ) ".
        "       AND ( correlativo = '".$correlativo."' )      ".
        "ORDER  BY linea                                    ";

        //dd($sql);
        $consulta = $pdo->prepare( $sql );

        $consulta->execute();

        $grilla = $consulta->fetchAll(PDO::FETCH_OBJ);

        return $grilla;
    }


    public static function compruebaFacturaEnSap( $docnum )
    {
        $retorno = false;

        $pdo = self::makePdoHana();

        $sql = "".
            "SELECT count(*) as conteo                               ".
            "FROM   HIS_DESA.desa_docenc                             ".
            "WHERE  empresa = 'DESA'                                 ".
            "       AND docnum = '{$docnum}'                         ".
            "       AND tipodocumento = 'FACT. AFECTA ELEC'          ";

        $consulta = $pdo->prepare( $sql );

        $consulta->execute();

        $grilla = $consulta->fetchAll(PDO::FETCH_OBJ);

/*
        if( $docnum == '4792056' )
        {
            dd(count($grilla));
        }
*/
        if( count($grilla) > 0)
        {
            $retorno = true;
        }

        return $retorno;

    }

}
