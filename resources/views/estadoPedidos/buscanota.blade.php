@include('funciones.traduce')
@extends('layouts.app')

@section('content')



    <div class="container">

        <div class="page-header">
            <h1>Nota Pedido <small>{{ $pedido->numero_pedido }}</small></h1>

            @foreach( traduceMensajeError( $cliente ) as $error )
                <p class="alert alert-danger">{{ $error }} </p>
            @endforeach

            @foreach( $errorestipoprecio as $error )
                <p class="alert alert-danger">{{ $error }} </p>
            @endforeach

            @foreach( $errorestipodispo as $error )
                <p class="alert alert-danger">{{ $error }} </p>
            @endforeach

            @foreach( $observaciones as $observacion )
                <p class="alert alert-warning">{{ $observacion }} </p>
            @endforeach



        </div>

        <!-- DATOS DEL CLIENTE -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Datos del cliente</h3>
            </div>

            <div class="panel-body table-responsive">

                <table class="table">

                    <tr>
                        <td width="150"><strong>Codigo Legal</strong></td>
                        <td width="300">: {{ $pedido->idcliente }}</td>

                        <td width="150"><strong>Local</strong></td>
                        <td>:{{ $pedido->idlocal }}</td>
                    </tr>

                    <tr>
                        <td><strong>Razon Social</strong></td>
                        <td>: {{ $cliente->RazonSocial }}</td>
                        <td><strong>Sigla</strong></td>
                        <td>: {{ $cliente->Sigla }}</td>
                    </tr>

                    <tr>
                        <td ><strong>Direccion</strong></td>
                        <td colspan="3">: {{ $cliente->Direccion }}</td>
                    </tr>

                    <tr>
                        <td><strong>Comuna</strong></td>
                        <td>: {{ $cliente->Comuna }}</td>
                        <td><strong>Ciudad</strong></td>
                        <td>: {{ $cliente->Ciudad }}</td>
                    </tr>


                </table>

            </div>

        </div>
        <!-- #DATOS DEL CLIENTE -->

        <!-- DATOS DEL PEDIDO -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Datos del pedido</h3>
            </div>

            <div class="panel-body table-responsive">

                <table class="table">

                    <tr>
                        <td width="150"><strong>Estado</strong></td>
                        <td width="300">: {{ aux_estados($pedido->sw_estado) }}</td>

                        <td width="150"></td>
                        <td></td>

                        <td width="150"></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td ><strong>Origen</strong></td>
                        <td colspan="6">: {{ $pedido->origen_notapedido.' | '.traduceOrigen( $pedido->origen_notapedido ) }}</td>

                    </tr>

                    <tr>
                        <td ><strong>Empresa</strong></td>
                        <td colspan="6">: {{ $pedido->idempresa.' | '.traduceEmpresa( $pedido->idempresa ) }}</td>
                    </tr>

                    <tr>
                        <td ><strong>Tipo Documento</strong></td>
                        <td >: {{ $pedido->Tipodocto }}</td>

                        <td ><strong>sw_iva</strong></td>
                        <td >: {{ $pedido->sw_iva }}</td>

                        <td ><strong>Cond. Pago</strong></td>
                        <td >: {{ $cliente->CondPago }}</td>
                    </tr>


                    <tr>
                        <td ><strong>Orden Compra</strong></td>
                        <td colspan="6">: {{ $pedido->orden_compra }}</td>
                    </tr>

                    <tr>
                        @if( $cliente->correlativo_flex > 0 )
                            <td ><strong>Correlativo Flex</strong></td>
                            <td colspan="6">: {{ $pedido->correlativo_flex }}</td>
                        @elseif($cliente->factura_sap > 0)
                            <td ><strong>Correlativo Factura</strong></td>
                            <td colspan="6">: {{ $pedido->factura_sap }}</td>
                        @else
                            <td ><strong>Correlativo Factura</strong></td>
                            <td >: <span class="badge">No facturado</span></td>
                        @endif
                    </tr>

                    <tr>
                        <td ><strong>Fecha Pedido</strong></td>
                        <td >: {{ traduceFecha( $cliente->fecha_pedido ) }}</td>

                        <td ><strong>Ingreso </strong></td>
                        <td >: {{ traduceHora( $pedido->hora_pedido ) }}</td>

                        <td ><strong>Recepcion </strong></td>
                        <td >: {{ traduceHora( $cliente->hora_recepcion ) }}</td>
                    </tr>

                    <tr>
                        <td ><strong>Fecha Proceso</strong></td>
                        <td >: {{ traduceFecha( $cliente->fecha_proceso ) }}</td>

                        <td ><strong>Fecha Entrega</strong></td>
                        <td colspan="6">: {{ traduceFecha( $cliente->fecha_entrega ) }}</td>
                    </tr>

                    <tr>
                        <td ><strong>Fecha Facturacion</strong></td>
                        <td >: {{ traduceFecha( $cliente->fecha_facturacion ) }}</td>

                        <td ><strong>Fecha O/C</strong></td>
                        <td colspan="6">: {{ traduceFecha( $cliente->Fecha_Orden_Compra ) }}</td>
                    </tr>
                    <tr>
                        <td ><strong>Usuario Aprobacion</strong></td>
                        <td >: {{ $cliente->usuario_aprfin  }}</td>

                        <td ><strong>Aprob. Automatica</strong></td>
                        <td >: {{ $cliente->vb_apraut  }}</td>

                        <td ><strong>Aprob. Manual</strong></td>
                        <td colspan="6">: {{ $cliente->vb_aprfin  }}</td>

                    </tr>

                    <tr>
                        <td ><strong>Credito Maximo</strong></td>
                        <td >: {{ $cliente->vb_cremax  }}</td>

                        <td ><strong>Deuda Ctacte</strong></td>
                        <td >: {{ $cliente->vb_deucta  }}</td>

                        <td ><strong>Protesto Vigente</strong></td>
                        <td colspan="6">: {{ $cliente->vb_provig  }}</td>

                    </tr>

                    <tr>
                        <td ><strong>Protesto Histórico</strong></td>
                        <td >: {{ $cliente->vb_prohis  }}</td>

                        <td ><strong>Dias Atrasados</strong></td>
                        <td >: {{ $cliente->vb_diaatr  }}</td>

                        <td ><strong>Descuento</strong></td>
                        <td colspan="6">: {{ $cliente->vb_precio  }}</td>

                    </tr>

                    <tr>
                        <td ><strong>Stock</strong></td>
                        <td >: {{ $cliente->vb_stock  }}</td>

                        <td ><strong>Vendedor Nota</strong></td>
                        <td >: {{ $vendedor->nombre  }}</td>

                        <td ><strong>Vendedor Cliente</strong></td>
                        <td colspan="6">: {{ $cliente->Ejecutivo  }}</td>
                    </tr>

                    <tr>
                        <td ><strong>Local</strong></td>
                        <td >: {{ $cliente->idlocal.' | '.$sucursal->nombre  }}</td>

                        <td ><strong>Bodega </strong></td>
                        <td colspan="6">: {{ $cliente->idbodega.' | '.$bodega->nombre  }}</td>
                    </tr>


                </table>

            </div>

        </div>
        <!-- #DATOS DEL PEDIDO -->



        <!-- TABLA DE PRODUCTOS -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Tabla de productos</h3>
            </div>

            <div class="panel-body table-responsive">

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th></th>
                        <th class="text-right">|</th>
                        <th class="text-center" colspan="2">Cantidad	</th>
                        <th class="text-left">|</th>
                        <th></th>

                    </tr>

                    <tr>

                        <th class="text-center">Código</th>
                        <th class="text-center">Descripción</th>
                        <th class="text-center">Pedida	</th>
                        <th class="text-center">Desp.	</th>
                        <th class="text-left">Precio	</th>
                        <th class="text-center">Desc.	</th>

                    </tr>

                    </thead>
                    <tbody>

                        @foreach( $pedidos as $pedido )
                            <tr>
                                <td class="text-center">{{ $pedido->producto                        }}</td>
                                <td>{{ $pedido->GLOSA                                               }}</td>
                                <td class="text-center">{{ $pedido->cantidad_pedida                 }}</td>
                                <td class="text-center">{{ $pedido->cantidad_despachada             }}</td>
                                <td class="text-left">  {{ traduceClp($pedido->precio_unitario)     }}</td>
                                <td class="text-center">{{ '-'.(int)$pedido->descuento_unitario     }}</td>
                            </tr>
                        @endforeach
                        <!-- inicio de dettale-->
                        <tr>
                            <td></td><td></td><td></td>
                            <td colspan="6" >
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Resumen</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="text-left"><strong>Serv.Distri</strong></td>
                                        <td class="text-left">: {{ traduceClp($listaDetalle['serdis']) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left"><strong>Afecto. IVA</strong></td>
                                        <td class="text-left">: {{ traduceClp($listaDetalle['afeiva']) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left"><strong>Exento IVA</strong></td>
                                        <td class="text-left">: {{ traduceClp($listaDetalle['exeiva']) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left"><strong>I.V.A.</strong></td>
                                        <td class="text-left">: {{ traduceClp($listaDetalle['valiva']) }}</td>
                                    </tr>

                                    <tr>
                                        <td class="text-left"><strong>ILA Vinos</strong></td>
                                        <td class="text-left">: {{ traduceClp($listaDetalle['ilavin']) }}</td>
                                    </tr>

                                    <tr>
                                        <td class="text-left"><strong>ILA Cerveza</strong></td>
                                        <td class="text-left">: {{ traduceClp($listaDetalle['ilacer']) }}</td>
                                    </tr>

                                    <tr>
                                        <td class="text-left"><strong>ILA Licores</strong></td>
                                        <td class="text-left">: {{ traduceClp($listaDetalle['ilalic']) }}</td>
                                    </tr>

                                    <tr>
                                        <td class="text-left"><strong>ILA Whisky</strong></td>
                                        <td class="text-left">: {{ traduceClp($listaDetalle['ilawhi']) }}</td>
                                    </tr>

                                    <tr>
                                        <td class="text-left"><strong>ILA Bebida</strong></td>
                                        <td class="text-left">: {{ traduceClp($listaDetalle['ilabeb']) }}</td>
                                    </tr>

                                    <tr>
                                        <td class="text-right"><strong>Total</strong></td>
                                        <td class="text-left"><strong>: {{ traduceClp($listaDetalle['valtot']) }}</strong></td>
                                    </tr>

                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    <!-- fin de dettale-->

                    </tbody>




                </table>

            </div>

        </div>
        <!-- #TABLA DE PRODUCTOS -->
        <div class="row row col-lg-12" >

            <button type="button"
                    class="btn btn-primary btn-lg btn-block"
                    onclick='window.location ="{{ url('estadopedidos').'/'.$cliente->fecha_pedido }}"'>
                <span class="glyphicon glyphicon-circle-arrow-left" aria-hidden="true"></span>{{ ' Volver' }}
            </button>

        </div>

    </div>

@endsection