<?php

function aux_estados( $estado )
{
    switch ($estado) {
        case 'I':
            $estado = 'No procesado';
            break;
        case 'P':
            $estado = 'En proceso';
            break;
        case 'F':
            $estado = 'Facturado';
            break;
        case 'R':
            $estado = 'Rechazado';
            break;
        case 'N ':
            $estado = 'Nulo';
            break;
    }
    return $estado;

}

function traduceOrigen( $Origen_notapedido )
{
    $norigen = '';
    switch ($Origen_notapedido) {
        case 'O':
            $norigen = 'Oficina';
            break;
        case 'M':
            $norigen = 'Movil';
            break;
        case 'H':
            $norigen = 'HTC';
            break;
    }
    return $norigen;

}

function traduceEmpresa( $idempresa )
{
    $nomEmpresa = '';
    switch ($idempresa) {
        case '1':
            $nomEmpresa = 'Distribucion Excelencia S.A.';
            break;
        case '3':
            $nomEmpresa = 'Distribucion Excelencia (DesaZofri)';
            break;
        case '4':
            $nomEmpresa = 'Distribuidora La CAV';
            break;

    }

    return $nomEmpresa;

}

function traduceFecha( $fecha )
{

    $dia    = substr( $fecha, -2 );
    $mes    = substr( $fecha, -4, 2 );
    $anio   = substr( $fecha,-8, 4 );

    return $dia.'/'.$mes.'/'.$anio;

}

function traduceHora( $hora )
{

    $horario    = substr( $hora, -6, 2 );
    $minutero   = substr( $hora, -4, 2 );
    $segundero  = substr( $hora, -2, 2 );

    return $horario.':'.$minutero.':'.$segundero;

}

function traduceClp( $valor )
{
    $valor_entero   = round($valor);
    $valorClp       = number_format($valor_entero);
    return '$ '.$valorClp;
}

function traduceMensajeError( $clienteaux )
{
    $vb_cremax = $clienteaux->vb_cremax;
    $vb_deucta = $clienteaux->vb_deucta;
    $vb_provig = $clienteaux->vb_provig;
    $vb_prohis = $clienteaux->vb_prohis;

    $arr_errores = [];

    ( $vb_cremax=='N' )? array_push($arr_errores,'Crédito máximo excedido'):null;
    ( $vb_deucta=='N' )? array_push($arr_errores,'Atraso en cuenta corriente'):null;
    ( $vb_provig=='N' )? array_push($arr_errores,'Protestos vigentes'):null;
    ( $vb_prohis=='N' )? array_push($arr_errores,'Protestos históricos'):null;

    return $arr_errores;

}

function traduceFactura( $pedido )
{
    return is_null( $pedido->factura_sap )? $pedido->factura_desa: $pedido->factura_sap;
}


function traduceFechaSap( $fecha )
{
    $anio = substr($fecha, -29,4 );
    $mes  = substr($fecha, -24,2 );
    $dia  = substr($fecha, -21,2 );
    $n_fecha = $dia.'-'.$mes.'-'.$anio;

    return $n_fecha;
}