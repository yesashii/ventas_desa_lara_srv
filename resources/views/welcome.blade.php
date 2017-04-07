@extends( 'layouts.app' )


@section('content')

    <div class="container">

        <div class="row">

            <div class="col-md-8 col-md-offset-2">

                <div class="panel panel-info">
                    <div class="panel-body">
                        <span class="glyphicon glyphicon-chevron-right"></span> Ingreso de pedido
                    </div>
                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-md-8 col-md-offset-2">

                <div class="panel panel-info">
                    <div class="panel-body">
                        <span class="glyphicon glyphicon-chevron-right"></span> Anular de pedido
                    </div>
                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-md-8 col-md-offset-2">

                <a href="{{ url('/estadopedidos') }}">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <span class="glyphicon glyphicon-chevron-right"></span> Estados de pedidos
                        </div>
                    </div>
                </a>

            </div>

        </div>


        <div class="row">

            <div class="col-md-8 col-md-offset-2">

                <div class="panel panel-info">
                    <div class="panel-body">
                        <span class="glyphicon glyphicon-chevron-right"></span> Clientes
                    </div>
                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-md-8 col-md-offset-2">

                <div class="panel panel-info">
                    <div class="panel-body">
                        <span class="glyphicon glyphicon-chevron-right"></span> Cuentas corrientes
                    </div>
                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-md-8 col-md-offset-2">

                <div class="panel panel-info">
                    <div class="panel-body">
                        <span class="glyphicon glyphicon-chevron-right"></span> Resumen CtaCte
                    </div>
                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-md-8 col-md-offset-2">

                <div class="panel panel-info">
                    <div class="panel-body">
                        <span class="glyphicon glyphicon-chevron-right"></span> Facturas
                    </div>
                </div>

            </div>

        </div>


        <div class="row">

            <div class="col-md-8 col-md-offset-2">

                <div class="panel panel-info">
                    <div class="panel-body">
                        <span class="glyphicon glyphicon-chevron-right"></span> Avance Remuneraciones
                    </div>
                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-md-8 col-md-offset-2">

                <div class="panel panel-info">
                    <div class="panel-body">
                        <span class="glyphicon glyphicon-chevron-right"></span> Encuesta
                    </div>
                </div>

            </div>

        </div>

    </div>


@endsection
