@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Inicio de sesión</div>
                    <div class="panel-body">

                        <!-- formulario -->
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('login') }}">
                            {!! csrf_field() !!}


                            <!-- SELECCION DE EMPRESA -->
                            <div class="form-group ">
                                <label for="idempresa" class="col-md-4 control-label">Empresa</label>

                                <div class="col-md-6">

                                    <select class="form-control" name="idempresa" id="idempresa">

                                    @foreach( $empresas as $empresa )

                                        <option value="{{ $empresa->idempresa }}">{{ $empresa->nombre }}</option>

                                    @endforeach


                                    </select>

                                </div>
                            </div>
                                <!-- #SELECCION DE EMPRESA -->


                                <!-- CORREO ELECTRÓNICO -->
                                <div class="form-group{{ count($errors) > 0 ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">Correo electrónico</label>

                                    <div class="col-md-6">
                                        <input type="email" class="form-control" name="email" value="{{ isset($_COOKIE["email"])? $_COOKIE["email"] : '' }}">

                                        @if( count($errors) > 0 )
                                            @foreach( $errors->all() as $error )
                                                <span class="help-block">
                                                    <strong>{{ $error }}</strong>
                                                </span>
                                            @endforeach
                                        @endif

                                    </div>
                                </div>
                                <!-- #CORREO ELECTRÓNICO -->

                                <div class="form-group ">
                                <label class="col-md-4 control-label">Contraseña</label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control"
                                           name="psw_pedidos"
                                           value="{{ isset($_COOKIE["psw_pedidos"])? $_COOKIE["psw_pedidos"] : '' }}">

                                    @if ($errors->has('psw_pedidos'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('psw_pedidos') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"
                                                   {{ isset($_COOKIE["remember"])? $_COOKIE["remember"] : '' }}
                                            > Recordar en este equipo
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-sign-in"></i>Ingresar
                                    </button>

                                    <a class="btn btn-link" href="{{ url('password/email') }}">Olvidé mi contraseña</a>
                                </div>
                            </div>
                        </form>
                        <!-- #formulario -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
