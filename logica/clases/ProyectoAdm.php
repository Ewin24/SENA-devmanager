<?php

class Rh_proyecto
{
    public $id;
    public $fecha_solicitud;
    public $estado;
    public $id_proyecto;
    public $id_usuario;

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = " SELECT id, fecha_solicitud, estado, id_proyecto, id_usuario
                                FROM    rh_proyectos
                                WHERE   $campo = $valor";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
                print_r($campo);
            }
            //asignacion de los datos
            $this->id = $campo['id'];
            $this->fecha_solicitud = $campo['fecha_solicitud'];
            $this->estado = $campo['estado'];
            $this->id_proyecto = $campo['id_proyecto'];
            $this->id_usuario = $campo['id_usuario'];
        }
    }
}

class Habilidad_Proyectos
{
    public $id;
    public $id_proyecto;
    public $id_habilidad;

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "  SELECT 	id, id_proyecto, id_habilidad
                                FROM 	proyectos_habilidades
                                WHERE   $campo = $valor";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
                print_r($campo);
            }
            //asignacion de los datos
            $this->id = $campo['id'];
            $this->id_proyecto = $campo['id_proyecto'];
            $this->id_habilidad = $campo['id_habilidad'];
        }
    }
}

class ProyectoAdm
{    ////////////////////////////////////////////////////////////////////////////////////
    /* REGION lógica negocio para administrar proyectos */
    public static function getDatosJson($filtro, $orden, $Opcion="", $idProyecto="")
    {
        // if ($filtro == null || $filtro == '')
        //     $filtro = '';
        // else
        //     $filtro = "WHERE $filtro";
        // if ($orden == null || $orden == '')
        //     $orden = '';
        // else
        //     $orden = "ORDER BY $orden";
        $datos = array();

        switch ($Opcion) {

            case "HabRequerida":
                $filtroHabRequerida = "id_proyecto = '$idProyecto'";
                $cadenaSQL ="   SELECT 	id, id_proyecto, id_habilidad
                                FROM 	proyectos_habilidades
                                WHERE 	$filtroHabRequerida $orden";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
                for ($i = 0; $i < count($resultado); $i++) {
                    $HabRequerida = new Habilidad_Proyectos($resultado[$i], null);
                    $datos[$i] = $HabRequerida;
                }
                break;

            case "HabDisponible":
                $filtroHabDisponible = "id_proyecto = '$idProyecto'";
                $ordenHabDisponible   = "";
                $cadenaSQL ="   SELECT 	id, id_proyecto, id_habilidad
                                FROM 	proyectos_habilidades
                                WHERE 	$filtroHabDisponible $ordenHabDisponible";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
                for ($i = 0; $i < count($resultado); $i++) {
                    $HabRequerida = new Habilidad_Proyectos($resultado[$i], null);
                    $datos[$i] = $HabRequerida;
                }
                break;

            case "TrabAsignados":
                $filtroTrabRequeridas = "id_proyecto = '$idProyecto' AND estado = 'A'";
                $cadenaSQL ="   SELECT 	id, fecha_solicitud, estado, id_proyecto, id_usuario
                                FROM 	rh_proyectos
                                WHERE 	$filtroTrabRequeridas $orden";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
                for ($i = 0; $i < count($resultado); $i++) {
                    $HabRequerida = new Rh_proyecto($resultado[$i], null);
                    $datos[$i] = $HabRequerida;
                }
                break;

            case "TrabDisponible":
                $filtroTrabDisponible = "id_proyecto = '$idProyecto' AND estado = 'E'";
                $cadenaSQL ="   SELECT 	id, fecha_solicitud, estado, id_proyecto, id_usuario
                                FROM 	rh_proyectos
                                WHERE 	$filtroTrabDisponible $orden";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
                for ($i = 0; $i < count($resultado); $i++) {
                    $HabRequerida = new Rh_proyecto($resultado[$i], null);
                    $datos[$i] = $HabRequerida;
                }
                break;

            default: //proyectos
                // $datos = Proyecto::getListaEnObjetos($filtro, $orden);
        }

        // $json_data = array(
        //     //"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
        //     "recordsTotal"    => intval( count($datos) ),  // total number of records
        //     // "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
        //     "data"            => $datos   // total data array
        //     );

        //echo json_encode($json_data);  // send data as json format
        return $datos;
    }

    public static function getHabilidadesRequeridas($idProyectoSeleccionado){
        return ProyectoAdm::getDatosJson(null, null, "HabRequerida", $idProyectoSeleccionado);
    }

    public static function getHabilidadesDisponibles($idProyectoSeleccionado){
        return ProyectoAdm::getDatosJson(null, null, "HabDisponible", $idProyectoSeleccionado);
    }

    public static function getTrabajadoresAsignados($idProyectoSeleccionado){
        // tabla rh_proyectos donde estado A=(Asignado) para el proyecto pasado por parametro
        return ProyectoAdm::getDatosJson(null, null, "TrabAsignados", $idProyectoSeleccionado);
    }

    public static function getTrabajadoresDisponibles($idProyectoSeleccionado){
        // tabla habilidades donde estado diferente A=Asignado para el proyecto pasado por parametro
        return ProyectoAdm::getDatosJson(null, null, "TrabDisponible", $idProyectoSeleccionado);
    }

    public static function cargarTablasHijas($idProySeleccionado){

        if ($idProySeleccionado != null || $idProySeleccionado != '')
        {
            //// Definiendo la lógica de negocio dentro de la clase
            $datHabAsignados = ProyectoAdm::getHabilidadesRequeridas($idProySeleccionado);
            $datHabDisponibles = ProyectoAdm::getHabilidadesDisponibles($idProySeleccionado);
            $datTrabAsignados = ProyectoAdm::getTrabajadoresAsignados($idProySeleccionado);
            $datTrabDisponibles = ProyectoAdm::getTrabajadoresDisponibles($idProySeleccionado);
        }
        return [$datHabAsignados, $datHabDisponibles, $datTrabAsignados, $datTrabDisponibles];
    }
}