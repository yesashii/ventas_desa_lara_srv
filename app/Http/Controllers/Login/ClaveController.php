<?php

namespace App\Http\Controllers\Login;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;

use App\Modelos\Dim_vendedores;
use App\Modelos\Dim_empresas;
use App\Modelos\DIM_PASSWORDRESET;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;



class ClaveController extends Controller
{
    /**
     * Carga la ventana de recuperacion de contraseña.
     *
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $empresas = Dim_empresas::whereIn('idempresa',[1,3,4])->get() ;

        return view( 'Login.claveResetEmail' ,[

            'empresas' => $empresas,

        ]);
    }


    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail(Request $request)
    {

        $validez = $this->verificaMail( $request->idempresa, $request->email );

        //dd( $validez );

         if( !$validez )
         {

             return Redirect::to('password/email')->withErrors('No existen registros con los parametros ingresados');

         }else{

             $usuarios = Dim_vendedores::where('email', '=', $request->email )
                 ->where('idempresa', '=', $request->idempresa )
                 ->get();

             if( isset( $usuarios[0] ) )
             {
                 $user = $usuarios[0];

                 $p_reset = new DIM_PASSWORDRESET();

                 $p_reset->idvendedor   = $user->idvendedor;
                 $p_reset->idempresa    = $user->idempresa;
                 $p_reset->nombre       = $user->nombre;
                 $p_reset->email        = $user->email;
                 $p_reset->token        = strtr( bcrypt( rand(11111,99999) ),".","" );
                 $p_reset->idvendedor   = $user->idvendedor;

                 $p_reset->save();

                 $uri = '?email='.$p_reset->email.'&token='.$p_reset->token.'&idempresa='.$p_reset->idempresa;

                 Mail::send('emails.password', [
                     'uri' => $uri
                 ], function ($m) use ($user) {
                     //$m->from('hello@app.com', 'Your Application');

                     $m->to($user->email, $user->nombre)->subject('Your Reminder!');
                 });

             }



         }

    }


    public function reset( Request $request )
    {

       return view('Login.reset', [

           'request' => $request,

       ]);

    }



    /**
     * verifica si existen los datos ingresados
     *
     *
     * @param $idEmpresa
     * @param $mail
     * @return bool
     */
    public function verificaMail( $idEmpresa, $mail )
    {

        $resultado = Dim_vendedores::where('email', '=', $mail )
            ->where('idempresa', '=', $idEmpresa )
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


}
