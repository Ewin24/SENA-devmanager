<?php

class Proyecto
{

    protected $id;
    protected $nombre;
    protected $descripcion;
    protected $estado;
    protected $fechaInicio;
    protected $fechaFinalizacion;
    protected $directorProyecto;

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "select id, nombre, descripcion, estado, fechaInicio, fechaFinalizacion, directorProyecto from proyecto where $campo = $valor";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
            }
            //asignacion de los datos
            $this->id = $campo['id'];
            $this->nombre = $campo['nombre'];
            $this->descripcion = $campo['descripcion'];
            $this->estado = $campo['estado'];
            $this->fechaInicio = $campo['fechaInicio'];
            $this->fechaFinalizacion = $campo['fechaFinalizacion'];
            $this->directorProyecto = $campo['directorProyecto'];
        }
    }

    public function getId()
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

    public function getEstado()
    {
        return $this->estado;
    }

    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    public function getFechaFinalizacion()
    {
        return $this->fechaFinalizacion;
    }

    public function getDirectorProyecto()
    {
        return $this->directorProyecto;
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
        $this->fechaInicio = $fechaInicio;
    }

    public function setFechaFinalizacion($fechaFinalizacion)
    {
        $this->fechaFinalizacion = $fechaFinalizacion;
    }

    public function setDirectorProyecto($directorProyecto)
    {
        $this->directorProyecto = $directorProyecto;
    }

    public function getProyectoPorNombre($nombre)
    {
        $cadenaSQL = "select nombre, descripcion, estado, fechaInicio, fechaFinalizacion, directorProyecto from proyecto where nombre = '$nombre'";
        return ConectorBD::ejecutarQuery($cadenaSQL)[0];
    }

    public function getProyectoPorDirector($director)
    {
        $cadenaSQL = "select nombre, descripcion, estado, fechaInicio, fechaFinalizacion, directorProyecto from proyecto where directorProyecto = '$director'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function getProyectoPorEstado($estado)
    {
        $cadenaSQL = "select nombre, descripcion, estado, fechaInicio, fechaFinalizacion, directorProyecto from proyecto where estado = '$estado'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function getProyectoPorFecha($fecha)
    {
        $cadenaSQL = "select nombre, descripcion, estado, fechaInicio, fechaFinalizacion, directorProyecto from proyecto where fechaInicio = '$fecha'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function getProyectoPorFechaFinalizacion($fecha)
    {
        $cadenaSQL = "select nombre, descripcion, estado, fechaInicio, fechaFinalizacion, directorProyecto from proyecto where fechaFinalizacion = '$fecha'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function getProyectoPorFechaInicio($fecha)
    {
        $cadenaSQL = "select nombre, descripcion, estado, fechaInicio, fechaFinalizacion, directorProyecto from proyecto where fechaInicio = '$fecha'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    //eliminar un proyecto
    public function eliminarProyecto($id)
    {
        $cadenaSQL = "delete from proyecto where id = '$id'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    //adicionar un proyecto
    public function adicionarProyecto($nombre, $descripcion, $estado, $fechaInicio, $fechaFinalizacion, $directorProyecto)
    {
        $cadenaSQL = "insert into proyecto (nombre, descripcion, estado, fechaInicio, fechaFinalizacion, directorProyecto) values( '$nombre', '$descripcion', '$estado', '$fechaInicio', '$fechaFinalizacion', '$directorProyecto')";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    //modificar un proyecto
    public function modificarProyecto($id, $nombre, $descripcion, $estado, $fechaInicio, $fechaFinalizacion, $directorProyecto)
    {
        $cadenaSQL = "update proyecto set nombre = '$nombre', descripcion = '$descripcion', estado = '$estado', fechaInicio = '$fechaInicio', fechaFinalizacion = '$fechaFinalizacion', directorProyecto = '$directorProyecto' where id = '$id'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '')
            $filtro = '';
        else
            $filtro = "where $filtro";
        if ($orden == null || $orden == '')
            $orden = '';
        else
            $orden = "order by $orden";

        $cadenaSQL = "select idProyecto, nombre, descripcion, estado, fechaInicio, fechaFinalizacion, directorProyecto from proyecto $filtro $orden";
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
}
