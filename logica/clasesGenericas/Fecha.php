<?php
//clase para el manejo de fechas en el programa
class Fecha
{
    //opcion de calculo de dos fechas 
    public static function calcularDiferenciaFechasEnSegundos($fecha1, $fecha2)
    {
        $inicio = strtotime($fecha1); //devuelve numero de segundos desde 1/1/1970
        $fin = strtotime($fecha2);
        $diferencia = $fin - $inicio;
        // $diferencia = $diferencia / 60 / 60 / 24;
        return $diferencia;
    }

    public static function calcularDiferenciaFechasEnDias($fecha1, $fecha2)
    {
        $fechaInicio = new DateTime($fecha1);
        $fechaFin = new DateTime($fecha2);
        $diferencia = $fechaInicio->diff($fechaFin);
        //print_r($diferencia);
        return $diferencia->days;
    }
}
