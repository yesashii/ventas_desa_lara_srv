

@include('layouts.header')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Ingrese el correo electrónico asociado a su cuenta</div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
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

                        <!-- correo electronico -->
                        <div class="form-group{{ (count($errors) > 0 ) ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Correo electrónico</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if( count($errors) > 0 )
                                    @foreach( $errors->all() as $error )
                                        <span class="help-block">
                                                    <strong>{{ $error }}</strong>
                                                </span>
                                    @endforeach
                                @endif

                            </div>
                        </div>
                        <!-- correo electronico -->

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-envelope"></i>Enviar enlace al correo electrónico
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')