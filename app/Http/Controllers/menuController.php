<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


use Carbon\Carbon;

class menuController extends Controller
{
    private function traeListaFechas()
    {
        $fechas = [];

        $fechaactual    = Carbon::now('America/Santiago');

        $diaactual      = $fechaactual->day;

        $mesactual      = ( $fechaactual->month < 10 )? '0'.$fechaactual->month: $fechaactual->month;



        for( $i = 1; $i < ($diaactual + 1 ); $i++ )
        {

            $dd     = ( $i < 10 )? '0'.$i: $i;
            $mm     = $mesactual;
            $yyyy   = $fechaactual->year;

            $fechas += [
                $yyyy.$mm.$dd => $dd.'-'.$mm.'-'.$yyyy,
            ];

        }

        return $fechas;

    }


    public function estadoPedidos()
    {

        dd( $this->traeListaFechas() );



        return view('estadoPedidos.index', compact('fecha'));
    }


}

