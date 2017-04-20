@include('funciones.traduce')
@extends('layouts.app')

@section('content')

    <div class="container">

       <!-- Eleccion de fecha -->
        <div class="row justify-content-center" >
            <form action="{{ route('estadopedidos') }}" method="post">
                {{ csrf_field() }}

                <input type="hidden" name="idvendedor" value="{{ $idvendedor }}">
                <input type="hidden" name="nombre" value="{{ $nombre }}">

                <div class="row col-lg-12">


                    <div class="input-group input-group-lg">

                        <select class="form-control" name="fecha" id="fecha">

                            @foreach( $fechas as $key=>$fecha )
                                @if( $fecha_r == $key )
                                    <option selected value="{{ $key }}">{{ $fecha }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $fecha }}</option>
                                @endif

                            @endforeach

                        </select>
                        <span class="input-group-btn">
				        <button type="submit" class="btn btn-info" id="mifecha">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                        </button>
			        </span>
                    </div>

                </div>
            </form>
        </div>
        <!-- #Eleccion de fecha -->


        <!-- Pedidos Desa ERP -->
        <div style="margin-top: 3em;" ></div>

        <div class="row col-lg-12" >
            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading">Pedidos DesaERP <span class="badge">{{ count($pedidos) }}</span></div>
                @if( count($pedidos) > 0 )
                    <div class="panel-body">

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-striped">

                                <thead>
                                <tr>
                                    <th>Pedido      </th>
                                    <th>Hora        </th>
                                    <th>Sigla       </th>
                                    <th>Razonsocial </th>
                                    <th>Estado      </th>
                                    <th>Detalle     </th>
                                    <th>Externo     </th>
                                    <th>Despacho    </th>
                                </tr>
                                </thead>

                                @foreach( $pedidos as $pedido)

                                    <tr {{ ($pedido->sw_estado == 'R')?"class=danger":'' }} >
                                        <td>
                                            <a href="{{ url('buscanota').'/'.$pedido->numero_pedido }}"
                                               data-toggle="tooltip"
                                               title="{{'Nota Pedido: '.$pedido->numero_pedido}}">
                                                {{ substr($pedido->numero_pedido, -4).' ' }}
                                                <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>
                                            </a>
                                        </td>
                                        <td>{{ substr($pedido->hora_pedido,-6,2).':'.substr($pedido->hora_pedido,-4,2).':'.substr($pedido->hora_pedido,-2,2) }}</td>
                                        <td>{{ $pedido->Sigla   }}</td>
                                        <td>{{ $pedido->RazonSocial   }}</td>
                                        <td>{{ aux_estados( $pedido->sw_estado )   }}</td>

                                        <td>
                                            @if( $pedido->factura_desa or $pedido->factura_sap )

                                                @if( \App\Modelos\sap\Pedidos_s::compruebaFacturaEnSap($pedido->factura_sap)  )

                                                    <a href="{{ url('buscafactura').'/'.traduceFactura( $pedido ).'/'.$pedido->idempresa }}"
                                                       data-toggle="tooltip"
                                                       title="{{'Ver factura'}}">
                                                        {{ traduceFactura( $pedido ) }}
                                                        <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>
                                                    </a>

                                                @else

                                                    <a data-toggle="tooltip"
                                                       title="{{'Factura en Proceso de traspaso'}}">
                                                        {{ traduceFactura( $pedido ) }}
                                                    </a>

                                                @endif

                                            @endif
                                        </td>

                                        <td>{{ $pedido->pedido_externo   }}</td>
                                        <td>{{ 'Despacho '   }}<span class="glyphicon glyphicon-new-window" aria-hidden="true"></span> </td>
                                    </tr>

                                @endforeach

                            </table>
                        </div>

                    </div>
                @else

                    <div class="panel-body">
                        <p> <span class="glyphicon glyphicon-alert" aria-hidden="true"></span> {{ ' Sin pedidos para la fecha selccionada' }} </p>
                    </div>

                @endif




            </div>
        </div>
        <!-- #Pedidos Desa ERP -->



        <!-- Pedidos CallCenter -->
        <div style="margin-top: 2em;" ></div>

        <div class="row col-lg-12" >
            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading">Pedidos Por Call Center <span class="badge">{{ count($pedidos_call) }}</span></div>

                @if( count( $pedidos_call ) > 0 )
                    <div class="panel-body">
                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-striped">

                                <thead>
                                <tr>
                                    <th>Pedido              </th>
                                    <th>Nombre del cliente  </th>
                                    <th>Estado              </th>
                                    <th>Detalle             </th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach( $pedidos_call as $pedido_call)

                                    <tr>
                                        <td>{{ substr($pedido_call->numero_pedido, -4)   }}</td>
                                        <td>{{ $pedido_call->Nombre_cliente              }}</td>
                                        <td>{{ aux_estados( $pedido_call->est )          }}</td>
                                        <td>{{ aux_estados( $pedido_call->Detalle )      }}</td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else

                    <div class="panel-body">
                        <p> <span class="glyphicon glyphicon-alert" aria-hidden="true"></span> {{ ' Sin pedidos para la fecha selccionada' }} </p>
                    </div>

                @endif

            </div>
        </div>
        <!-- #Pedidos CallCenter -->

        <div class="row row col-lg-12" >

            <button type="button"
                    class="btn btn-primary btn-lg btn-block"
                    onclick='window.location ="{{ route('home') }}"'>
                <span class="glyphicon glyphicon-circle-arrow-left" aria-hidden="true"></span>{{ ' Volver' }}
            </button>

        </div>

    </div>

@endsection