<?php

class Proyecto
{

    protected $idProyecto;
    protected $nombre;
    protected $descripcion;
    protected $estado; //terminado, en ejecucion, por iniciar
    protected $fechaInicio;
    protected $fechaFinalizacion;
    protected $IdDirector; //puede tener como foranea el director de proyecto

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = " SELECT id, nombre, descripcion, estado, fecha_inicio, fecha_fin, id_usuario
                FROM    proyectos
                WHERE   $campo = $valor";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
                print_r($campo);
            }
            //asignacion de los datos
            $this->idProyecto = $campo['id'];
            $this->nombre = $campo['nombre'];
            $this->descripcion = $campo['descripcion'];
            $this->estado = $campo['estado'];
            $this->fechaInicio = $campo['fecha_inicio'];
            $this->fechaFinalizacion = $campo['fecha_fin'];
            $this->IdDirector = $campo['id_usuario'];
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
            return "Ejecución";
        }

        if ($diferenciaFechas > 0) {
            return "Terminado";
        }

        $diferenciaFechas = Fecha::calcularDiferenciaFechasEnSegundos($fechaActual, $this->fechaInicio);
        if ($diferenciaFechas < 0) {
            return "Pendiente";
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

    public function getIdDirector()
    {
        return $this->IdDirector;
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

    public function setIdDirector($IdDirector)
    {
        $this->IdDirector = $IdDirector;
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
    /*
    public function getProyectoPorFechaFinalizacion($fecha)
    {
        $cadenaSQL = "SELECT nombre, descripcion, estado, fecha_inicio, fecha_fin, id_usuario FROM proyectos WHERE fechaFinalizacion = '$fecha'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }
    public function getProyectoPorFechaInicio($fecha)
    {
        $cadenaSQL = "SELECT nombre, descripcion, estado, fecha_inicio, fecha_fin, id_usuario FROM proyectos WHERE fechaInicio = '$fecha'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }
    */

    //eliminar un proyecto
    public function eliminar($idProyecto)
    {
        $cadenaSQL = "DELETE FROM proyectos WHERE idProyecto = '$idProyecto'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    //adicionar un proyecto
    public function guardar()
    {
        $cadenaSQL = "INSERT INTO proyectos (nombre, descripcion, estado, fechaInicio, fechaFinalizacion, id_usuario) 
                      VALUES( '$this->nombre', '$this->descripcion', '$this->estado', '$this->fechaInicio', '$this->fechaFinalizacion', '$this->IdDirector')";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    //modificar un proyecto
    public function modificar($idProyectoAnterior)
    {
        $cadenaSQL = "UPDATE proyectos 
                      SET   nombre = '$this->nombre', 
                            descripcion = '$this->descripcion', 
                            estado = '$this->estado', 
                            fechaInicio = '$this->fechaInicio', 
                            fechaFinalizacion = '$this->fechaFinalizacion', 
                            id_usuario = '$this->IdDirector' 
                      WHERE idProyecto = '$idProyectoAnterior'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

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

        $cadenaSQL = "SELECT id, nombre, descripcion, estado, fecha_inicio, fecha_fin, id_usuario FROM proyectos $filtro $orden";
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
