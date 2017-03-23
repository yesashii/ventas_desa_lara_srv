<?php

namespace App\Http\Controllers\Login;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use App\Modelos\Dim_empresas;
use App\Modelos\Dim_vendedores;

use Illuminate\Support\Facades\Redirect;


class LoginController extends Controller
{
    /**
     * inicializa el logeo.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()

    {
        return view('Login.login',[
            'empresas' => Dim_empresas::traeEmpresas(),
        ]);
    }

    /**
     * Verifica si existe usuario y contraseña, si es así, redirige dentro del sitio
     *
     *
     * @param Request $request
     * @return mixed
     */
    public function verificaCredenciales( Request $request )

    {
        $mailValido = Dim_vendedores::verificaMail( $request->email );


        if( $mailValido )
        {

            if( Dim_vendedores::existeVendedor( $request->email, $request->idempresa, $request->psw_pedidos ) )
            {
                $vendedor = Dim_vendedores::traeVendedor($request->email, $request->idempresa );
                session_start();

                $_SESSION['usuario'] = $vendedor->nombre;

                $this->manejaCookieLogin( $request );


                return Redirect::to('/');


            }else{

                return Redirect::to('/login')->withErrors('Las credenciales no son válidas');

            }

        }else{

            return Redirect::to('/login')->withErrors("El correo \" {$request->email} \" no se encuentra en nuestros registros.");

        }


    }


    /**
     * ejecuta el deslogeo del sitio y redirige al login
     *
     * @return mixed
     */
    public function logout()

    {

        session_start();

        session_destroy();

        return Redirect::to('/login');

    }


    public function manejaCookieLogin( Request $request )
    {

        if( isset(  $request->remember ) )
        {

            if( $request->remember == 'on' )
            {
                setcookie("email", $request->email);
                setcookie("psw_pedidos", $request->psw_pedidos);
                setcookie("remember", 'checked');
            }

        }else{

            if( isset( $_COOKIE['email'] ) )
            {

                setcookie("email", "");
                setcookie("psw_pedidos", "");
                setcookie("remember", '');

            }

        }

    }



}
