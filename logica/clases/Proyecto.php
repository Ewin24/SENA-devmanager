<?php

class Proyecto
{

    public $id;
    public $nombre;
    public $descripcion;
    public $estado; //terminado, en ejecucion, por iniciar
    public $fecha_inicio;
    public $fecha_fin;
    public $id_director; //puede tener como foranea el director de proyecto
    public $correo_director; //puede tener como foranea el director de proyecto

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = " SELECT id, nombre, descripcion, estado, fecha_inicio, fecha_fin, id_usuario, correo
                FROM    proyectos
                WHERE   $campo = $valor";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
                print_r($campo);
            }
            //asignacion de los datos
            $this->id = $campo['id'];
            $this->nombre = $campo['nombre'];
            $this->descripcion = $campo['descripcion'];
            $this->estado = $campo['estado'];
            $this->fecha_inicio = $campo['fecha_inicio'];
            $this->fecha_fin = $campo['fecha_fin'];
            $this->id_director = $campo['id_usuario'];
            $this->correo_director = $campo['correo'];
        }
    }

    public function getIdProyecto()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    // obtener el estado haciendo uso de la clase Fecha,  si la fecha de inicio es mayor a la de fin, entonces esta en estado "cerrado"  y si no, esta en estado "abierto" 
    // y si la fecha de inicio es igual a la de fin, esta en estado "abierto"  y si la fecha de inicio es menor a la de fin, esta en estado "abierto"  
    public function getEstado()
    {
        return $this->estado;
    }

    public function getFechaInicio()
    {
        return $this->fecha_inicio;
    }

    public function getFechaFinalizacion()
    {
        return $this->fecha_fin;
    }

    public function getIdDirector()
    {
        return $this->id_director;
    }

    public function getCorreoDirector()
    {
        return $this->correo_director;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function setFechaInicio($fechaInicio)
    {
        $this->fecha_inicio = $fechaInicio;
    }

    public function setFechaFinalizacion($fechaFinalizacion)
    {
        $this->fecha_fin = $fechaFinalizacion;
    }

    public function setIdDirector($IdDirector)
    {
        $this->id_director = $IdDirector;
    }

    public function getProyectoPorNombre($nombre)
    {
        $cadenaSQL = "SELECT nombre, descripcion, estado, fecha_inicio, fecha_fin, id_usuario FROM proyectos LIKE nombre = '%$nombre%'";
        return ConectorBD::ejecutarQuery($cadenaSQL)[0];
    }

    public function getProyectoPorDirector($DirectorId) //se busca el director como foranea
    {
        $cadenaSQL = "SELECT usuario.* FROM proyectos INNER JOIN usuarios ON usuario.identificacion = '$DirectorId' WHERE usuario.tipoUsuario = 'D';";
        return ConectorBD::ejecutarQuery($cadenaSQL);
        //consulta el director y devuelve todos los datos
    }

    public function getProyectoPorEstado($estado)
    {
        $cadenaSQL = "SELECT nombre, descripcion, estado, fecha_inicio, fecha_fin, id_usuario FROM proyectos WHERE estado = '$estado'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function getProyectoPorFecha($fechaIni, $fechaFin)
    {
        $cadenaSQL = "SELECT nombre, descripcion, estado, fecha_inicio, fecha_fin, id_usuario FROM proyectos BETWEEN '$fechaIni' AND '$fechaFin'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }
  
    ////////////////////////////////////////////////////////////////////////////////////
    /* REGION CRUD proyectos */
    //eliminar un proyecto
    public function eliminar($idProyecto)
    {
        $cadenaSQL = "DELETE FROM proyectos WHERE id = '$idProyecto'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    //adicionar un proyecto
    public function guardar()
    {
        $cadenaSQL = "INSERT INTO proyectos 
                            (id, nombre, descripcion, estado, fecha_inicio, fecha_fin, id_usuario)
                      VALUES(   $this->id',
                                $this->nombre',
                                $this->descripcion',
                                $this->estado',
                                $this->fecha_inicio',
                                $this->fecha_fin',
                                $this->id_director')";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    //modificar un proyecto
    public function modificar($idProyectoAnterior)
    {
        $cadenaSQL = "UPDATE proyectos 
                      SET   nombre = '$this->nombre', 
                            descripcion = '$this->descripcion', 
                            estado = '$this->estado', 
                            fecha_inicio = '$this->fecha_inicio', 
                            fecha_fin = '$this->fecha_fin', 
                            id_usuario = '$this->id_director' 
                      WHERE id = '$idProyectoAnterior'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    ////////////////////////////////////////////////////////////////////////////////////
    /* REGION mapear proyectos */
    public static function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '')
            $filtro = '';
        else
            $filtro = "WHERE $filtro";
        if ($orden == null || $orden == '')
            $orden = '';
        else
            $orden = "ORDER BY $orden";

        $cadenaSQL = "  SELECT 		p.id, p.nombre, p.descripcion, p.estado, p.fecha_inicio, p.fecha_fin, p.id_usuario, u.correo
                        FROM 		(proyectos p
                        INNER JOIN 	usuarios u ON u.id = p.id_usuario)
                        $filtro $orden";
        // print_r($cadenaSQL);
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = Proyecto::getLista($filtro, $orden);
        $lista = array();
        for ($i = 0; $i < count($resultado); $i++) {
            $proyecto = new Proyecto($resultado[$i], null);
            $lista[$i] = $proyecto;
        }
        return $lista;
    }

    public static function getListaEnJson($filtro, $orden)
    {
        $datos = Proyecto::getListaEnObjetos($filtro, $orden);
        return json_encode($datos);
    }

}
