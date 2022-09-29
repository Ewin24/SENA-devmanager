<?php
class Habilidad
{
    private $idHabilidad;
    private $nombre;
    private $descripcion;

    //constructor con array

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT idHabilidad,nombre,descripcion FROM habilidad WHERE $campo = $valor;";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
                print_r($campo);
            }
            //asignacion de los datos
            $this->idHabilidad_FK = $campo['idHabilidad'];
            $this->nombre = $campo['nombre'];
            $this->descripcion = $campo['descripcion'];
        }
    }

    //get
    public function getIdHabilidad($campo,$valor)
    {
        $cadenaSQL = "SELECT idHabilidad FROM habilidad WHERE $campo = '$valor';";
        $id = ConectorBD::ejecutarQuery($cadenaSQL);
        $this->idHabilidad = $id;
        return $this->idHabilidad;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    //set
    public function setIdHabilidad($idHabilidad)
    {
        $this->idHabilidad = $idHabilidad;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function guardar()
    {
        //echo $this->nombre, $this->descripcion;
        $cadenaSQL = "INSERT INTO  habilidad (nombre, descripcion ) VALUES ('$this->nombre', '$this->descripcion')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM habilidad WHERE idHabilidad = $this->idHabilidad;";
        ConectorBD::ejecutarQuery($cadenaSQL);
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

        $cadenaSQL = "SELECT idHabilidad, nombre, descripcion FROM habilidad $filtro $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = Habilidad::getLista($filtro, $orden);
        $lista = array();
        for ($i = 0; $i < count($resultado); $i++) {
            $idHabilidad = new Habilidad($resultado[$i], null);
            $lista[$i] = $idHabilidad;
        }
        return $lista;
    }
}
