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
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;



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
     * Envia el correo con el password reset
     *
     *
     * @param Request $request
     * @return mixed
     */
    public function postEmail(Request $request)
    {

        $validez = $this->verificaMail( $request->idempresa, $request->email );

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

                 $p_reset->idvendedor       = $user->idvendedor;
                 $p_reset->idempresa        = $user->idempresa;
                 $p_reset->nombre           = $user->nombre;
                 $p_reset->email            = $user->email;
                 $p_reset->token            = strtr( bcrypt( rand(11111,99999) ),".","" );
                 $p_reset->fecha_creacion   = Carbon::now('America/Santiago');
                 $p_reset->idvendedor       = $user->idvendedor;

                 $p_reset->save();

                 $uri = '?email='.$p_reset->email.'&token='.$p_reset->token.'&idempresa='.$p_reset->idempresa;

                 Mail::send('emails.password', [
                     'uri' => $uri
                 ], function ($m) use ($user) {
                     //$m->from('hello@app.com', 'Your Application');

                     $m->to($user->email, $user->nombre)->subject('Cambio de clave');
                 });


                 return view('Login.mensajes.envio_correo',[
                     'email' => $p_reset->email,
                 ]);

             }



         }

    }


    /**
     * Verifica y envía la información a la ventana de reseteo de contraseña
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reset( Request $request )
    {

        $datosReseteo = DIM_PASSWORDRESET::where('token','=',$request->token)
                        ->where('email','=',$request->email)
                        ->where('idempresa','=',$request->idempresa)
                        ->get();

        if( isset($datosReseteo[0])  )
        {

            return view('Login.reset', [

                'request' => $request,

            ]);

        }else{

            return view('Login.errores.403');
        }


    }


    /**
     * Cambia la contraseña
     *
     *
     * @param Request $request
     * @return mixed
     */
    public function passwordReset( Request $request )

    {

        $validator = Validator::make($request->all(), [
            'email'                     => 'required',
            'password'                  => 'required',
            'password_confirmation'     => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }elseif (  $request->password <> $request->password_confirmation  )
        {
            $validator->errors()->add('password', 'Las claves ingresadas no coinciden');

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();

        }else {

            $vendedor = Dim_vendedores::traeVendedorReset( $request->email,$request->idempresa  );

            $vendedor->psw_pedidos = $request->password_confirmation;

            $vendedor->save();

            DIM_PASSWORDRESET::eliminaReseteo( $request->email, $request->token );

            return view('Login.mensajes.clave_cambiada');


        }


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
