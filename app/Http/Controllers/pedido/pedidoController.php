<?php

namespace App\Http\Controllers\pedido;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modelos\raw\Pedidos_r;// sqlServer
use App\Modelos\sap\Pedidos_s;// SAP
use App\Modelos\Dim_vendedores;

use Carbon\Carbon;

class pedidoController extends Controller
{

    /**
     * carga la ventana principal del estado de pedidos
     *
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function estadoPedidos( $fecha = null )
    {
        $vendedor       = Dim_vendedores::traeVendedor( $_SESSION['email'], $_SESSION['idempresa'] );
        $idvendedor     = $vendedor->idvendedor;

        if( $_SESSION['idempresa'] == 4 )// Lacav
        {
            $pedidos        = Pedidos_r::traeListaPedidoLacav( $this->FechaActualFormateada(),$idvendedor );

        }else{

            $pedidos        = Pedidos_r::traeListaPedidoDesa( $this->FechaActualFormateada(),$idvendedor );
        }

        $nombre         = $vendedor->nombre;

        if( isset($fecha) )
        {
            $pedidos_call   = Pedidos_r::traePedidosPorCallCenter( $vendedor->nombre,$idvendedor,$fecha );
            $fecha_r        = $fecha;
        }else{

            $pedidos_call   = Pedidos_r::traePedidosPorCallCenter( $vendedor->nombre,$idvendedor,$this->FechaActualFormateada() );
            $fecha_r        = '';
        }


        $fechas = $this->traeListaFechas();

        return view('estadoPedidos.index',
            compact('fechas',
                'pedidos',
                'idvendedor',
                'fecha_r',
                'pedidos_call',
                'nombre'));

    }

    public function buscarPorFecha( Request $request )
    {
        //dd($request);

        $fecha_r    = $request->fecha;
        $idvendedor = $request->idvendedor;
        $nombre     = $request->nombre;
        $pedidos    = Pedidos_r::traeListaPedidoDesa( $fecha_r,$idvendedor );
        $pedidos_call   = Pedidos_r::traePedidosPorCallCenter( $nombre,$idvendedor,$fecha_r );
        //dd($pedidos);
        $fechas     = $this->traeListaFechas();

        return view('estadoPedidos.index',
            compact('fechas',
                'pedidos',
                'idvendedor',
                'fecha_r',
                'nombre',
                'pedidos_call'
            ));

    }



    /**
     * Devuelve la lista con las fechas que llenan el combo box
     *
     *
     * @return array
     */
    private function traeListaFechas()
    {
        $fechas = [];

        $fechaactual    = Carbon::now('America/Santiago');

        $diaactual      = $fechaactual->day;

        $mesactual      = ( $fechaactual->month < 10 )? '0'.$fechaactual->month: $fechaactual->month;



        for( $i = 1; $i < ($diaactual + 1 ); $i++ )
        {

            $dd     = ( $i < 10 )? '0'.$i: $i;
            $mm     = $mesactual;
            $yyyy   = $fechaactual->year;

            $fechas += [
                $yyyy.$mm.$dd => $dd.'-'.$mm.'-'.$yyyy,
            ];

        }

        krsort( $fechas ); // oredena el arreglo de mayor a menor
        return $fechas;

    }


    /**
     * retorna la fecha con el formato que acepta el sistema.
     *
     *
     * @return string
     */
    private function FechaActualFormateada()
    {
        $fechaactual    = Carbon::now('America/Santiago');

        $diaactual      = $fechaactual->day;

        $mesactual      = ( $fechaactual->month < 10 )? '0'.$fechaactual->month: $fechaactual->month;

        $dd     = ( $diaactual < 10 )? '0'.$diaactual: $diaactual;
        $mm     = $mesactual;
        $yyyy   = $fechaactual->year;

        return $yyyy.$mm.$dd;
    }

    /**
     * Accion que se ejecuta cuando se da a buscar la fecha.
     *
     * @param $numpedido
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function buscaNota( $numpedido )
    {


        $vendedor       = Dim_vendedores::traeVendedor( $_SESSION['email'], $_SESSION['idempresa'] );
        $pedido         = Pedidos_r::traePedidoPorNumero( $numpedido );
        $cliente        = Pedidos_r::traeClientePedido( $pedido->idempresa, $pedido->numero_pedido );


        $pedidos        = Pedidos_r::traeListaDelPedido($pedido->idempresa, $pedido->numero_pedido);

        $sucursal       = Pedidos_r::traeSucursalPorId( $cliente->idsucursal );
        $bodega         = Pedidos_r::traeBodegaPorId( $cliente->idbodega );

        $listaDetalle   = Pedidos_r::detallesDelPedido( $pedido->numero_pedido , $pedido->idempresa );

        $errorestipoprecio  = Pedidos_r::traeErrDeTipoPrecio( $pedido->numero_pedido, $pedido->idempresa );
        $errorestipodispo   = Pedidos_r::traeErrDeTipoDisponibilidad( $pedido->numero_pedido, $pedido->idempresa );
        $observaciones      = Pedidos_r::traeObservaciones( $pedido->numero_pedido, $pedido->idempresa );

        return view('estadoPedidos.buscanota', compact('pedido',
            'cliente',
            'vendedor',
            'sucursal',
            'bodega',
            'pedidos',
            'listaDetalle',
            'errorestipoprecio',
            'errorestipodispo',
            'observaciones'));

    }


    public function buscaFactura( $numfactura, $idempresa )
    {

        $numfacturaFormat   =  $this->formateaNumFactura( $numfactura );
        $desa_docenc_sap    = Pedidos_s::traeEncabezadoDocumentoSapDesa($numfacturaFormat);

        $pedidos            = Pedidos_s::traeGrillaFacturaSap( $desa_docenc_sap->CORRELATIVO );

        //dd($pedidos);

        //dd($desa_docenc_sap);

        return view('estadoPedidos.verFactura', compact( 'numfacturaFormat','desa_docenc_sap', 'pedidos' ));

    }


    /**
     * Le agrega los ceros faltantes al numero de factura
     *
     * @param $numfactura
     * @return string
     */
    private function formateaNumFactura( $numfactura )
    {
        $largoDoc = strlen($numfactura);

        while ( $largoDoc < 10 )
        {
            $numfactura = '0'.$numfactura;
            $largoDoc = strlen($numfactura);
        }

        return $numfactura;
    }





}
