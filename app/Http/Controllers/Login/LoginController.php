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
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()

    {
        $empresas = Dim_empresas::whereIn('idempresa',[1,3,4])->get() ;

        return view('Login.login',[
            'empresas' => $empresas,
        ]);
    }


    /**
     * verifica si existe el correo en la base de datos
     *
     * @param $mail
     * @return bool
     */
    public function verificaMail( $mail )
    {

        $resultado = Dim_vendedores::where('email', '=', $mail )
            ->where('sw_vigente', '=', 'S' )
            ->where('email', '<>', '' )
            ->get();

        if( isset( $resultado[0] ) )
        {
            return true;
        }else{
            return false;
        }

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
        $mailValido = $this->verificaMail( $request->email );

        if( $mailValido )
        {

            $usuarios = Dim_vendedores::where('email', '=', $request->email)
                ->Where('idempresa', '=', $request->idempresa)
                ->Where('psw_pedidos', '=', $request->psw_pedidos)
                ->get();


            if( isset( $usuarios[0] ) )
            {
                session_start();

                $_SESSION['usuario'] = $usuarios[0]->nombre;

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

       // dd($request);


    }



}
