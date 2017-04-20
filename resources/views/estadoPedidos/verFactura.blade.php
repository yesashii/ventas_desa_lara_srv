@include('funciones.traduce')
@extends('layouts.app')

@section('content')

    <div class="container table-responsive">

        <div class="row">

            <div class="col-lg-3"> </div>
            <div class="col-lg-9">

                <table class="center-block" >

                    @if( $desa_docenc_sap->VIGENCIA == "A" )

                        <tr>
                            <td style="color:#CC0000 "><strong>Documento NULO</strong></td>
                        </tr>

                    @endif

                    @if( $desa_docenc_sap->VIGENCIA == "N" )

                        <tr>
                            <td style="color:#CC0000 "><strong>Documento No Vigente</strong></td>
                        </tr>

                @endif

                <!-- Numero de factura -->
                    <tr>
                        <td class="text-info"> <strong>Distribucion y Excelencia</strong> </td>
                        <td >

                            <table border='2' width='180px' height='60px'  bordercolorlight='#008000' bordercolordark='#008000' bordercolor='#008000'>
                                <tr>
                                    <td >

                                        <div class="factura_1" >{{'FACT. AFECTA ELEC'}}                     </div>
                                        <div class="factura_1" ><strong>{{ $numfacturaFormat }} </strong>   </div>
                                        <div class="factura_1" >{{$desa_docenc_sap->CORRELATIVO}}           </div>

                                    </td>
                                </tr>
                            </TABLE>

                        </td>

                    </tr>
                    <!-- #Numero de factura -->

                    <!-- Detalle de la factura -->
                    <tr>
                        <td>

                            <table >

                                <tr>
                                    <td width="150px">Nombre</td>
                                    <td>: {{ $desa_docenc_sap->RAZONSOCIAL }}</td>
                                </tr>

                                <tr>
                                    <td >Id Cliente</td>
                                    <td>: {{ $desa_docenc_sap->CTACTE.'    '.$desa_docenc_sap->SIGLA.'    '.traduceFechaSap($desa_docenc_sap->FECHAEMISION) }}</td>
                                </tr>

                                <tr>
                                    <td >Dirección</td>
                                    <td>: {{ $desa_docenc_sap->DIRECCIONDESPACHO }}</td>
                                </tr>

                                <tr>
                                    <td >Cond. Pago</td>
                                    <td>: {{ $desa_docenc_sap->CONDICIONPAGO }}</td>
                                </tr>

                                <tr>
                                    <td >Vend Cliente</td>
                                    <td>: {{ $desa_docenc_sap->VENDEDORCLIENTE }}</td>
                                </tr>

                                <tr>
                                    <td >Vend Factura</td>
                                    <td>: {{ $desa_docenc_sap->VENDEDORFACTURA }}</td>
                                </tr>

                                <tr>
                                    <td >Local-bodega</td>
                                    <td>: {{ $desa_docenc_sap->SUCURSAL.' '.$desa_docenc_sap->BODEGA }}</td>
                                </tr>

                                <tr>
                                    <td >Referencia</td>
                                    <td>: {{ $desa_docenc_sap->REFERENCIA }}</td>
                                </tr>

                                <tr>
                                    <td >Fecha OC</td>
                                    <td>: {{ $desa_docenc_sap->FECHAOC }}</td>
                                </tr>

                                <tr>
                                    <td >Nota Pedido</td>
                                    <td>: {{ $desa_docenc_sap->NOTAPEDIDO }}</td>
                                </tr>

                            </table>


                        </td>
                    </tr>
                    <!-- #Detalle de la factura -->

                    <tr>
                        <td>

                            <table class="table table-striped">

                                <thead>
                                <tr>
                                    <th>Producto </th>
                                    <th>Glosa    </th>
                                    <th>Cantidad </th>
                                    <th>Precio   </th>
                                    <th>Neto     </th>
                                    <th>Descuento</th>
                                </tr>
                                </thead>

                                <tbody>

                                @foreach( $pedidos as $pedido )

                                    <tr>
                                        <td>{{ $pedido->PRODUCTO                }}</td>
                                        <td>{{ $pedido->GLOSA                   }}</td>
                                        <td>{{ $pedido->CANTIDAD                }}</td>
                                        <td>{{ traduceClp($pedido->PRECIO)      }}</td>
                                        <td>{{ traduceClp($pedido->NETO)        }}</td>
                                        <td>{{ '% '.round( $pedido->DESCUENTO ) }}</td>
                                    </tr>

                                @endforeach
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td colspan="2" > <strong>Totales</strong>
                                        <table class="table">
                                            <tr>
                                                <td>Flete</td>
                                                <td>: {{ traduceClp( $desa_docenc_sap->FLETE ) }}</td>
                                            </tr>

                                            <tr>
                                                <td>Afecto</td>
                                                <td>: {{ traduceClp( $desa_docenc_sap->AFECTO ) }}</td>
                                            </tr>

                                            <tr>
                                                <td>Impuestos</td>
                                                <td>: {{ traduceClp( $desa_docenc_sap->IMPUESTO ) }}</td>
                                            </tr>

                                            <tr >
                                                <td><strong>Total</strong></td>
                                                <td><strong>: {{ traduceClp( $desa_docenc_sap->TOTAL ) }}</strong></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>

                            </table>

                        </td>
                    </tr>

                    <tr>
                        <td>

                            <TABLE>
                                <TR>
                                    <TD valign="top"><INPUT TYPE="button" value="<< Atras"      onclick="history.back();history.back()"></TD>
                                    <TD valign="top"><input type="button" value="Imprimir"      onClick="window.print()"></TD>
                                    <TD valign="top">
                                        <FORM METHOD=POST ACTION='/palm/ruta.asp'>
                                            <INPUT TYPE='hidden' name='documento' value='0004789553'>
                                            <INPUT TYPE='hidden' name='empresa' value='DESA'>
                                            <INPUT TYPE='hidden' name='tipodocto' value='FACT. AFECTA ELEC'>

                                            <INPUT TYPE='submit' value='Info Despacho'>
                                        </FORM>
                                    </TD>
                                    <TD valign="top">

                                    </TD>


                                </TR>
                            </TABLE>

                        </td>
                    </tr>

                </table>

            </div>


        </div>



    </div>

@endsection