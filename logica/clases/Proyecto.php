<?php

class Proyecto
{

    protected $idProyecto;
    protected $nombre;
    protected $descripcion;
    protected $estado; //terminado, en ejecucion, por iniciar
    protected $fechaInicio;
    protected $fechaFinalizacion;
    protected $idUsuario_FK; //puede tener como foranea el director de proyecto

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "select idProyecto, nombre, descripcion, estado, fechaInicio, fechaFinalizacion, idUsuario_FK from proyecto where $campo = $valor";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
            }
            //asignacion de los datos
            $this->idProyecto = $campo['idProyecto'];
            $this->nombre = $campo['nombre'];
            $this->descripcion = $campo['descripcion'];
            $this->estado = $campo['estado'];
            $this->fechaInicio = $campo['fechaInicio'];
            $this->fechaFinalizacion = $campo['fechaFinalizacion'];
            $this->idUsuario_FK = $campo['idUsuario_FK'];
        }
    }

    public function getIdProyecto()
    {
        return $this->idProyecto;
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
        $fechaActual = date('Y-m-d H:i:s');
        echo $fechaActual;
        $diferenciaFechas = Fecha::calcularDiferenciaFechasEnSegundos($fechaActual, $this->fechaFinalizacion);
        if (strtotime($fechaActual) > strtotime($this->fechaInicio) && strtotime($fechaActual) < strtotime($this->fechaFinalizacion)) {
            return "En ejecucion";
        }

        if ($diferenciaFechas > 0) {
            return "Terminado";
        }

        $diferenciaFechas = Fecha::calcularDiferenciaFechasEnSegundos($fechaActual, $this->fechaInicio);
        if ($diferenciaFechas < 0) {
            return "Por ejecutar";
        }
    }

    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    public function getFechaFinalizacion()
    {
        return $this->fechaFinalizacion;
    }

    public function getIdUsuario_FK()
    {
        return $this->idUsuario_FK;
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

    public function setIdUsuario_FK($idUsuario_FK)
    {
        $this->idUsuario_FK = $idUsuario_FK;
    }

    public function getProyectoPorNombre($nombre)
    {
        $cadenaSQL = "select nombre, descripcion, estado, fechaInicio, fechaFinalizacion, idUsuario_FK from proyecto where nombre = '$nombre'";
        return ConectorBD::ejecutarQuery($cadenaSQL)[0];
    }

    public function getProyectoPorDirector($idDirector) //se busca el director como foranea
    {
        $cadenaSQL = "SELECT usuario.* FROM `proyecto` INNER JOIN usuario ON usuario.identificacion = '$idDirector' WHERE usuario.tipoUsuario = 'D';";
        return ConectorBD::ejecutarQuery($cadenaSQL);
        //consulta el director y devuelve todos los datos
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
    public function eliminar($idProyecto)
    {
        $cadenaSQL = "delete from proyecto where idProyecto = '$idProyecto'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    //adicionar un proyecto
    public function guardar()
    {
        $cadenaSQL = "insert into proyecto (nombre, descripcion, estado, fechaInicio, fechaFinalizacion, idUsuario_FK) values( '$this->nombre', '$this->descripcion', '$this->estado', '$this->fechaInicio', '$this->fechaFinalizacion', '$this->idUsuario_FK')";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    //modificar un proyecto
    public function modificar($idProyectoAnterior)
    {
        $cadenaSQL = "update proyecto set nombre = '$this->nombre', descripcion = '$this->descripcion', estado = '$this->estado', fechaInicio = '$this->fechaInicio', fechaFinalizacion = '$this->fechaFinalizacion', idUsuario_FK = '$this->idUsuario_FK' where idProyecto = '$idProyectoAnterior'";
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

        $cadenaSQL = "select idProyecto, nombre, descripcion, estado, fechaInicio, fechaFinalizacion, idUsuario_FK from proyecto $filtro $orden";
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
