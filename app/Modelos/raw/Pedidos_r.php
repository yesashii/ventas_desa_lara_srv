<?php

namespace App\Modelos\raw;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pedidos_r extends Model
{
    //serverdesa.BDFlexline.flexline.producto
    private static $ped_pedidosenc  = 'sqlserver.desaerp.dbo.ped_pedidosenc';
    private static $dim_empresas    = 'sqlserver.desaerp.dbo.dim_empresas';
    private static $ctacte          = 'handheld.flexline.ctacte';
    private static $dim_vendedores  = 'sqlserver.desaerp.dbo.dim_vendedores';
    private static $dim_sucursales  = 'sqlserver.desaerp.dbo.dim_sucursales';
    private static $dim_bodegas     = 'sqlserver.desaerp.dbo.dim_bodegas';
    private static $ped_pedidosdet  = 'sqlserver.desaerp.dbo.ped_pedidosdet';
    private static $producto        = 'serverdesa.BDFlexline.flexline.producto';
    private static $ped_pedidosobs  = 'sqlserver.desaerp.dbo.ped_pedidosobs';
    private static $documento       = 'serverdesa.bdflexline.flexline.documento';

    //sql="SELECT * FROM sqlserver.desaerp.dbo.PED_PEDIDOSOBS where numero_pedido='" & np & "' and idempresa=" & idempresa




    public static function traeListaPedidoLacav( $fecha, $idempresa )
    {

        $documento = "".
            "right(													 ".
            "CAST( isnull(                                           ".
            "(                                                       ".
            "	select d.numero                                      ".
            "	from   serverdesa.bdflexline.flexline.documento as d ".
            "	where  d.empresa = 'LACAV'                           ".
            "	and    d.correlativo = p.correlativo_flex            ".
            "	and    d.tipodocto = 'FACT. AFECTA ELEC'             ".
            "), '') as varchar),4)                                   ";

        $select = "".
            "           p.numero_pedido 				as pedido,      ".
            "           p.hora_pedido         			as hora,        ".
            "           v.nombre                 		as nombre,      ".
            "           c.sigla                  		as sigla,       ".
            "           c.razonsocial            		as razonsocial,	".
            "           p.sw_estado              		as est,         ".
            "  		    ".$documento."   				as detalle, 	".
            "           isnull(p.pedido_externo,' ') 	as externo,		".
            "           p.total_neto                        			";

        return DB::table( DB::raw(self::$ped_pedidosenc." as p") )
            ->join( DB::raw(self::$dim_empresas." as e"), "p.idempresa",'=',"e.idempresa" )
            ->join( DB::raw(self::$ctacte." as c"), function ($join){
                $join->on('c.empresa', '=', 'e.nombre');
                $join->on('c.ctacte', '=', DB::raw("( p.idcliente + ' ' + Cast(p.idsucursal as nvarchar) )"));
            } )
            ->join( DB::raw(self::$dim_vendedores." as v"),function( $join ){
                $join->on( "v.idempresa",'=',"p.idempresa" );
                $join->on( "p.idvendedor",'=',"v.idvendedor" );
            }  )
            ->where( DB::raw( 'p.fecha_pedido' ), $fecha  )
            ->where( DB::raw( 'p.idempresa' ), $idempresa  )
            ->where( DB::raw( 'v.nombre' ),'NOT LIKE', '%CARGO%' )
            ->where( DB::raw( 'v.nombre' ),'NOT LIKE', '%OCD%' )
            ->select( DB::raw( $select ) )
            ->get();

    }



    /**
     * consulta que llena la tabla de pedidos desaerp, si la empresa es desa.
     *
     *
     * @param $fechaPedido
     * @param $idvendedor
     * @return mixed
     */
    public static function traeListaPedidoDesa( $fechaPedido, $idvendedor )
    {


        return DB::table( DB::raw(self::$ped_pedidosenc." as p") )
            ->join( DB::raw(self::$dim_empresas." as e"), "p.idempresa",'=',"e.idempresa" )
            ->join( DB::raw(self::$ctacte." as c"), function ($join){
                $join->on('c.empresa', '=', 'e.nombre');
                $join->on('c.ctacte', '=', DB::raw("( p.idcliente + ' ' + Cast(p.idsucursal as nvarchar) )"));
            } )
            ->where('p.fecha_pedido', '=', $fechaPedido)
            ->where('p.idvendedor',   '=', $idvendedor)
            ->orderBy('p.numero_pedido', 'asc')//->toSql();
            ->get();

    }


    /**
     * trae la lista de pedidos realizados por el call center.
     *
     * @param $nomUsuario
     * @param $idusuario
     * @param $fecha
     * @return mixed
     */
    public static function traePedidosPorCallCenter( $nomUsuario, $idusuario, $fecha )
    {

        $pd = "".
            "(SELECT p.numero_pedido, 														".
            "               RIGHT(p.numero_pedido, 4)                    AS nota,           ".
            "               LEFT(' ' + C.sigla + '' + C.razonsocial, 40) AS Nombre_cliente, ".
            "               p.sw_estado                                  AS est,            ".
            "               p.correlativo_flex                                              ".
            "        FROM   sqlserver.desaerp.dbo.ped_pedidosenc AS P                       ".
            "               INNER JOIN handheld.flexline.ctacte AS c                        ".
            "                       ON p.idcliente + ' '                                    ".
            "                          + Cast(p.idsucursal AS NVARCHAR) = c.ctacte          ".
            "        WHERE  ( c.ejecutivo = '" . $nomUsuario . "' )                         ".
            "               AND ( p.idvendedor <> " . $idusuario . " )                      ".
            "               AND ( P.fecha_pedido = " . $fecha . " )                         ".
            "               AND ( C.empresa = 'desa' )                                      ".
            "               AND ( c.tipoctacte = 'cliente' )                                ".
            "               AND p.idempresa = 1)AS pd                                       ";

        $nf = "".
            "(SELECT p.notaventa            			AS Numero_pedido,                           ".
            "       Isnull(p.nfactura, '') 			    AS nfactura,                                ".
            "CASE Len(nfactura) WHEN 0 THEN '' ELSE ''+right(nfactura,7)+'' END AS Detalle          ".
            "FROM   handheld.flexline.fx_pedido_pda 	AS P                                        ".
            "       INNER JOIN sqlserver.desaerp.dbo.dim_vendedores AS v                            ".
            "               ON p.vendedor = v.nombre                                                ".
            "WHERE  ( P.nfactura NOT LIKE 'X%' )                                                    ".
            "       AND ( V.idvendedor IN( 17, 18, 19, 300 ) )                                      ".
            "GROUP  BY p.notaventa,                                                                 ".
            "          p.nfactura ) AS NF                                                           ";


        return DB::table( DB::raw( $pd ) )
            ->leftJoin(DB::raw( $nf ), 'pd.numero_pedido', '=', 'NF.numero_pedido')
            ->orderBy('pd.nota', 'asc')//->toSql();
            ->get();
    }


    /**
     * trae una fila de un pedidosenc con el numero de pedido
     *
     * @param $num_pedido
     * @return mixed
     */
    public static function traePedidoPorNumero( $num_pedido )
    {
        $r_pedido = [];

        $pedidos =  DB::table( DB::raw( self::$ped_pedidosenc ) )
            ->where('numero_pedido',$num_pedido)
            ->get();
        foreach ( $pedidos as $pedido)
        {
            $r_pedido = $pedido;
        }
        return $r_pedido;

    }

    public static function traeClientePedido( $idempresa, $numPedido )
    {

        $empresa  = self::empresaXnumero( $idempresa );

        $r_cliente = [];

        $clientes =  DB::table( DB::raw( self::$ped_pedidosenc.' as p' ) )
                    ->join( DB::raw(self::$ctacte." as c"), DB::raw("p.idcliente +' '+ Cast(p.idsucursal AS nvarchar)"),'=',"c.CtaCte" )
                    ->where( 'c.Empresa', $empresa->nombre )
                    ->where( 'c.TipoCtaCte', 'CLIENTE' )
                    ->where( 'p.numero_pedido', $numPedido )
                    ->get();
        foreach ( $clientes as $cliente )
        {
            $r_cliente = $cliente;
        }

        return $r_cliente;

    }

    /**
     * Trae la empresa por su identificador
     *
     *
     * @param $idempresa
     * @return mixed
     */
    public static function empresaXnumero( $idempresa )
    {
        $r_empresa = [];
        $empresas = DB::table( DB::raw( self::$dim_empresas ) )
            ->where( 'idempresa', $idempresa )
            ->get();

        foreach ( $empresas as $empresa )
        {
            $r_empresa = $empresa;
        }
        return $r_empresa;
    }


    public static function traeSucursalPorId( $idsucursal )
    {
        $r_sucursal = [];

        $sucursales = DB::table( DB::raw( self::$dim_sucursales ) )
            ->where( 'idsucursal', $idsucursal )
            ->get();

        foreach ( $sucursales as $sucursal )
        {
            $r_sucursal = $sucursal;
        }
        return $r_sucursal;
    }


    /**
     * trae la fila de la bodega entregando el id
     *
     *
     * @param $idbodega
     * @return array
     */
    public static function traeBodegaPorId( $idbodega )
    {
        $r_bodega = [];

        $bodegas = DB::table( DB::raw( self::$dim_bodegas ) )
            ->where( 'idbodega', $idbodega )
            ->get();

        foreach ( $bodegas as $bodega )
        {
            $r_bodega = $bodega;
        }
        return $r_bodega;
    }


    /**
     * Consulta que llena la tabla de productos del detalle de pedidos
     *
     * @param $idempresa
     * @param $numPedido
     * @return mixed
     */
    public static function traeListaDelPedido( $idempresa, $numPedido )
    {

        $empresa    = self::empresaXnumero( $idempresa );

        $pedidios =  DB::table( DB::raw( self::$ped_pedidosdet.' as L' ) )
            ->join( DB::raw(self::$producto." as P"), "P.producto",'=',"L.Producto" )
            ->where( 'idempresa', $idempresa )
            ->where( 'L.numero_pedido', $numPedido )
            ->where( 'P.empresa', $empresa->nombre )
            ->get();

        return $pedidios;

    }

    /**
     * Trae una fila de productos indicando el codigo de producto
     *
     * @param $producto
     * @return mixed
     */
    public static function traeProductoConProducto( $producto )
    {
        $productos = DB::table( self::$producto )
            ->where( 'producto', $producto )
            ->where( 'empresa', 'DESA' )
            ->get();

        foreach ( $productos as $producto )
        {

            $r_producto = $producto;
        }

        return $r_producto;

    }

    /**
     * retorna los valores que seran usados en la vista del detalle del pedido
     *
     * @param $numPedido
     * @param $idempresa
     * @return array
     */
    public static function detallesDelPedido( $numPedido, $idempresa )
    {


        $listas =  DB::table( DB::raw( self::$ped_pedidosdet.' as T0' ) )
            ->join( DB::raw(self::$ped_pedidosenc." as T1"), function ($join) {
                $join->on('T0.numero_pedido', '=', 'T1.numero_pedido');
                $join->on('T0.idempresa', '=', 'T1.idempresa');
            })
            ->where( 'T0.numero_pedido', $numPedido )
            ->where( 'T0.idempresa', $idempresa )
            ->get();


        return  self::calculaLista( $listas );

    }




    /**
     * Retorna un arreglo con los valores del detalle de la tabla de productos.
     *
     * @param $listas
     * @return array
     */
    public static function calculaLista( $listas )
    {

        $serdis = 0; //Serv.Distri
        $afeiva = 0; //Afecto. IVA
        $exeiva = 0; //Exento IVA
        $valiva = 0; //I.V.A.
        $ilavin = 0; //ILA Vinos
        $ilacer = 0; //ILA Cerveza
        $ilalic = 0;//ILA Licores
        $ilawhi = 0;//ILA Whisky
        $ilabeb = 0;//ILA Bebida
        $valtot = 0;

        $valor_1 = 0.205;
        $valor_2 = 0.10;
        $valor_3 = 0.315;
        $iva     = 0.19;

        foreach ( $listas as $lista )
        {

            $neto           = $lista->monto_neto;

            $serdis         += $lista->monto_flete;
            $afeiva         += $neto;
            $auxProducto    =  self::traeProductoConProducto( $lista->producto );
            $rsporcentaje   =  $auxProducto->AnalisisProducto7;

            $producto_2     = substr($lista->producto, 0,2);
            $producto_7     = substr($lista->producto, 0,7);

            if( $producto_2 == "VN" or $producto_2 == "VI" )
            {
                $ilavin += ( $neto*$valor_1);
            }

            if( $producto_2 == "CI" and $producto_7 == "CI37011" and $producto_7 == "CI37015" )
            {
                $ilacer += ( $neto*$valor_1 );
            }

            if( $producto_7 == 'CI37011' or $producto_7 == 'CI37015' )
            {
                $ilabeb += ( $neto*$valor_2 );
            }

            if( $producto_2 == 'CN' )
            {
                $ilacer += ( $neto*$valor_1 );
            }

            if( $producto_2 == 'LI' or $producto_2 == 'PN' or $producto_2 == 'PI' )
            {
                $ilalic += ( $neto*$valor_3 );
            }

            if( $producto_2 == 'WH' )
            {
                $ilawhi += ( $neto*$valor_3 );
            }

            if( $producto_2 == 'BE' )
            {
                $ilabeb += ( $neto*($rsporcentaje/100) );
            }


        }// fin foreach

        $afeiva     += $serdis;
        $valiva     = $afeiva*$iva;
        $valtot     = $afeiva+$valiva+$ilavin+$ilacer+$ilalic+$ilawhi+$ilabeb+$exeiva;

        $array_retorno = [
            'exeiva' => $exeiva,
            'serdis' => $serdis,
            'afeiva' => $afeiva,
            'valiva' => $valiva,
            'ilavin' => $ilavin,
            'ilacer' => $ilacer,
            'ilalic' => $ilalic,
            'ilawhi' => $ilawhi,
            'ilabeb' => $ilabeb,
            'valtot' => $valtot,
        ];

       return $array_retorno;
    }

    /**
     * revisa los errores del tipo precio y los retorna en un arreglo
     *
     * @param $numPedido
     * @param $idempresa
     * @return array
     */
    public static function traeErrDeTipoPrecio( $numPedido, $idempresa )
    {

        $arr_errores = [];

        $errores = DB::table( DB::raw( self::$ped_pedidosenc.' as n' ) )
            ->join( DB::raw(self::$ped_pedidosdet." as l"), 'n.numero_pedido','=' ,'l.numero_pedido')
            ->join( DB::raw(self::$producto." as p"), 'l.producto','=', 'p.producto')
            ->join( DB::raw(self::$dim_empresas." as e"), function( $join ){
                $join->on( 'e.nombre','=', 'p.empresa' );
                $join->on( 'e.idempresa','=', 'n.idempresa' );
            })
            ->where( 'n.numero_pedido',  $numPedido )
            ->where( 'n.idempresa', $idempresa )
            ->where( 'n.vb_aprfin', 'S'  )
            ->whereIn( 'n.sw_estado', ['F','R']  )
            ->where( 'l.vb_precio', 'N'  )
            ->get();


        foreach ( $errores as $error )
        {
            array_push( $arr_errores, $error->producto.' | '.$error->glosa.' | Descuento No Aprobado');
        }

        return $arr_errores;

    }

    /**
     * trae la lista de errores del tipo stock
     *
     * @param $numPedido
     * @param $idempresa
     * @return array
     */
    public static function traeErrDeTipoDisponibilidad( $numPedido, $idempresa )
    {

        $arr_errores = [];

        $errores = DB::table( DB::raw( self::$ped_pedidosenc.' as n' ) )
            ->join( DB::raw(self::$ped_pedidosdet." as l"), 'n.numero_pedido','=' ,'l.numero_pedido')
            ->join( DB::raw(self::$producto." as p"), 'l.producto','=', 'p.producto')
            ->join( DB::raw(self::$dim_empresas." as e"), function( $join ){
                $join->on( 'e.nombre','=', 'p.empresa' );
                $join->on( 'e.idempresa','=', 'n.idempresa' );
            })
            ->where( 'n.numero_pedido',  $numPedido )
            ->where( 'n.idempresa', $idempresa )
            ->where( 'n.vb_aprfin', 'S'  )
            ->whereIn( 'n.sw_estado', ['F','R']  )
            ->where( 'l.vb_precio', 'S'  )
            ->where( DB::raw( '( l.cantidad_pedida - l.cantidad_despachada )','<>','0' ) )
            ->get();

        foreach ( $errores as $error )
        {
            array_push( $arr_errores, $error->producto.' | '.$error->glosa.' | faltan: '.$error->faltan.' unidades');
        }

        return $arr_errores;

    }

    public static function traeObservaciones( $numPedido, $idempresa )
    {

        $arr_observa = [];

        $observaciones = DB::table( self::$ped_pedidosobs )
            ->where('numero_pedido',$numPedido)
            ->where('idempresa',$idempresa)
            ->get();

        foreach ( $observaciones as $error )
        {
            array_push( $arr_observa, 'Obs: '.$error->observacion1);
        }

        return $arr_observa;

    }






}
