@include('layouts.header')

<div class="container">


        <div class="panel panel-success">
            <div class="panel-heading">
                <h1 align="center" class="panel-title ">Hemos enviado la información al correo: {{ $email }}</h1>
            </div>
            <div class="panel-body">

                <ul>
                    <li>Revisa tu correo y has clic en: "Actualizar su clave"                   </li>
                    <li>Si no te ha llegado el correo electrónico revisa el spam.               </li>
                    <li>Una vez que hay actualizado tu clave, debes volver a iniciar sessión    </li>
                </ul>

            </div>
        </div>

</div>

@include('layouts.footer')