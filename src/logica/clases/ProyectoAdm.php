<?php

require_once 'Habilidad.php';

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
    public static function getDatosCrudos($filtro, $orden, $Opcion = "", $idProyecto = "")
    {
        $datos = array();

        switch ($Opcion) {

            case "HabRequerida":
                $filtroHabRequerida = "id_proyecto = $idProyecto";
                $cadenaSQL = "   SELECT 	id, id_proyecto, id_habilidad
                                FROM 	proyectos_habilidades
                                WHERE 	$filtroHabRequerida $orden";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
                for ($i = 0; $i < count($resultado); $i++) {
                    $HabRequerida = new Habilidad_Proyectos($resultado[$i], null);
                    $datos[$i] = $HabRequerida;
                }
                break;

            case "HabDisponible":
                $filtroHabDisponible = "h.id not in (select id_habilidad from proyectos_habilidades where id_proyecto = $idProyecto)";
                $ordenHabDisponible   = "";
                $cadenaSQL = "  SELECT h.id, nombre, descripcion
                                FROM habilidades h
                                WHERE $filtroHabDisponible $ordenHabDisponible";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
                for ($i = 0; $i < count($resultado); $i++) {
                    $HabDisponible = new Habilidad($resultado[$i], null);
                    $datos[$i] = $HabDisponible;
                }
                break;

            case "TrabAsignados":
                $filtroTrabRequeridas = "id_proyecto = $idProyecto AND estado = 'A'";
                $cadenaSQL = "   SELECT 	id, fecha_solicitud, estado, id_proyecto, id_usuario
                                FROM 	rh_proyectos
                                WHERE 	$filtroTrabRequeridas $orden";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
                for ($i = 0; $i < count($resultado); $i++) {
                    $HabRequerida = new Rh_proyecto($resultado[$i], null);
                    $datos[$i] = $HabRequerida;
                }
                break;

            case "TrabDisponible":
                $filtroTrabDisponible = "rh.id_proyecto = $idProyecto AND estado = 'E'";
                $cadenaSQL = "   SELECT 	rh.id, fecha_solicitud, estado, id_proyecto, id_usuario
                                FROM 	usuarios u
                                INNER JOIN rh_proyectos rh ON rh.id_usuario =  u.id
                                WHERE 	$filtroTrabDisponible $orden
                                ORDER BY estado";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
                for ($i = 0; $i < count($resultado); $i++) {
                    $HabRequerida = new Rh_proyecto($resultado[$i], null);
                    $datos[$i] = $HabRequerida;
                }
                break;

            default:
                break;
        }
        return $datos;
    }

    public static function getHabilidadesRequeridas($idProyectoSeleccionado)
    {
        return ProyectoAdm::getDatosCrudos(null, null, "HabRequerida", $idProyectoSeleccionado);
    }

    public static function getHabilidadesDisponibles($idProyectoSeleccionado)
    {
        return ProyectoAdm::getDatosCrudos(null, null, "HabDisponible", $idProyectoSeleccionado);
    }

    public static function getTrabajadoresAsignados($idProyectoSeleccionado)
    {
        // tabla rh_proyectos donde estado A=(Asignado) para el proyecto pasado por parametro
        return ProyectoAdm::getDatosCrudos(null, null, "TrabAsignados", $idProyectoSeleccionado);
    }

    public static function getTrabajadoresDisponibles($idProyectoSeleccionado)
    {
        //     // tabla habilidades donde estado diferente A=Asignado para el proyecto pasado por parametro
        $resultado = ProyectoAdm::getDatosCrudos(null, null, "TrabDisponible", $idProyectoSeleccionado);
        return $resultado;
    }

    public static function cargarTablasHijas($idProySeleccionado)
    {

        if ($idProySeleccionado != null || $idProySeleccionado != '') {
            //// Definiendo la lógica de negocio dentro de la clase
            $datHabAsignados = ProyectoAdm::getHabilidadesRequeridas($idProySeleccionado);
            $datHabDisponibles = ProyectoAdm::getHabilidadesDisponibles($idProySeleccionado);
            $datTrabAsignados = ProyectoAdm::getTrabajadoresAsignados($idProySeleccionado);
            $datTrabDisponibles = ProyectoAdm::getTrabajadoresDisponibles($idProySeleccionado);
        }
        return [$datHabAsignados, $datHabDisponibles, $datTrabAsignados, $datTrabDisponibles];
    }

    //////////////////////////////////////////////////////////////////////////////////////
    //SECCION CRUD DE PROYECTOS
    public static function guardarObj($proyecto)
    {
        $cadenaSQL = "INSERT INTO proyectos (id, nombre, descripcion, estado, fecha_inicio, fecha_fin, id_usuario)
                      VALUES (  '$proyecto->id', 
                                '$proyecto->nombre', 
                                '$proyecto->descripcion', 
                                '$proyecto->estado', 
                                '$proyecto->fecha_inicio', 
                                '$proyecto->fecha_fin', 
                                '$proyecto->id_director')";
        // echo $cadenaSQL;
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function modificarObj($proyecto)
    {
        $cadenaSQL = "UPDATE proyectos 
                      SET   nombre = '$proyecto->nombre', 
                            descripcion = '$proyecto->descripcion', 
                            estado = '$proyecto->estado', 
                            fecha_inicio = '$proyecto->fecha_inicio', 
                            fecha_fin = '$proyecto->fecha_fin', 
                            id_usuario = '$proyecto->id_director' 
                      WHERE id = '$proyecto->id'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }
    public static function eliminarObj($idProyecto)
    {
        $cadenaSQL = "DELETE FROM proyectos WHERE id = '$idProyecto'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }
    public static function postularTrabajador($id_proyecto, $id_usuario)
    {

        $UUID = ConectorBD::get_UUIDv4();
        $fechaActual = date('Y-m-d');
        $cadenaSQL = "INSERT INTO rh_proyectos (id, fecha_solicitud, estado, id_proyecto, id_usuario) 
                        VALUES ('$UUID','$fechaActual','E','$id_proyecto','$id_usuario')";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    //////////////////////////////////////////////////////////////////////////////////////
    //SECCION CRUD DE HABILIDADES REQUERIDAS
    public static function insertarHabilidadProyecto($id_habilidad, $id_proyecto)
    {
        $UUID = ConectorBD::get_UUIDv4();
        $cadenaSQL = "INSERT INTO proyectos_habilidades (id, id_habilidad, id_proyecto)
                      VALUES (  '$UUID', 
                                '$id_habilidad', 
                                '$id_proyecto')";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function eliminarHabilidadProyecto($id, $id_proyecto)
    {
        $cadenaSQL = "DELETE FROM proyectos_habilidades 
                        WHERE id = '$id'
                        AND id_proyecto = '$id_proyecto'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    //////////////////////////////////////////////////////////////////////////////////////
    //SECCION CRUD DE HABILIDADES DISPONIBLES
    public static function guardarObjDisponible($habilidad)
    {
        $UUID = ConectorBD::get_UUIDv4();
        $cadenaSQL = "INSERT INTO habilidades (id, nombre, descripcion)
                      VALUES (  '$UUID', 
                                '$habilidad->nombre', 
                                '$habilidad->descripcion')";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function modificarObjDisponible($habilidad)
    {
        $cadenaSQL = "UPDATE habilidades 
                      SET   nombre = '$habilidad->nombre', 
                            descripcion = '$habilidad->descripcion' 
                      WHERE id = '$habilidad->id'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function eliminarObjDisponible($idHabilidad)
    {
        $cadenaSQL = "DELETE FROM habilidades WHERE id = '$idHabilidad'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    //////////////////////////////////////////////////////////////////////////////////////
    //SECCION CRUD DE TRABAJADORES ASIGNADOS
    public static function insertarTrabajadorProyecto($id_usuario, $id_proyecto)
    {
        $cadenaSQL = "UPDATE rh_proyectos 
            SET estado = 'A'
            WHERE id_usuario = '$id_usuario' AND id_proyecto = '$id_proyecto'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function eliminarTrabajadorProyecto($id_usuario, $id_proyecto)
    {
        $cadenaSQL = "DELETE FROM rh_proyectos WHERE id_proyecto= '$id_proyecto' AND id_usuario = '$id_usuario'";
        // $cadenaSQL = "UPDATE rh_proyectos
        //             SET estado = 'R'
        //             WHERE id_usuario = '$id_usuario'
        //             AND id_proyecto = '$id_proyecto'";
        $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
        return $resultado;
    }
}
