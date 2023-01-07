<?php

class Empresa
{

    private $id;
    private $nit;
    private $nombre;
    private $direccion;
    private $correo;
    private $telefono;
    private $nombreRepresentante;
    private $correoRepresentante;

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT id, nit, nombre, direccion, correo, telefono, nombre_representante, correo_representante 
                FROM empresas 
                WHERE $campo = $valor;";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
                print_r($campo);
            }
            //asignacion de los datos
            $this->id = $campo['id'];
            $this->nit = $campo['nit'];
            $this->nombre = $campo['nombre'];
            $this->direccion = $campo['direccion'];
            $this->correo = $campo['correo'];
            $this->telefono = $campo['telefono'];
            $this->nombreRepresentante = $campo['nombre_representante'];
            $this->correoRepresentante = $campo['correo_representante'];
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNit()
    {
        return $this->nit;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function getCorreo()
    {
        return $this->correo;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function getNombreRepresentante()
    {
        return $this->nombreRepresentante;
    }

    public function getCorreoRepresentante()
    {
        return $this->correoRepresentante;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setNit($nit): void
    {
        $this->nit = $nit;
    }

    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setDireccion($direccion): void
    {
        $this->direccion = $direccion;
    }

    public function setCorreo($correo): void
    {
        $this->correo = $correo;
    }

    public function setTelefono($telefono): void
    {
        $this->telefono = $telefono;
    }

    public function setNombreRepresentante($nombreRepresentante): void
    {
        $this->nombreRepresentante = $nombreRepresentante;
    }

    public function setCorreoRepresentante($correoRepresentante): void
    {
        $this->correoRepresentante = $correoRepresentante;
    }

    ///////////////////////////////////////////////////////////////////////////////////
    /* REGION CRUD empresas */

    //adicionar una empresa
    public function guardar()
    {
        //echo $this->nombre, $this->descripcion;
        $cadenaSQL = "INSERT INTO empresas 
                                  (id, nit, nombre, direccion, correo, telefono, nombre_representante, correo_representante) 
                      VALUES (  '{$this->id}', 
                                '{$this->nit}', 
                                '{$this->nombre}', 
                                '{$this->direccion}', 
                                '{$this->correo}', 
                                '{$this->telefono}', 
                                '{$this->nombreRepresentante}', 
                                '{$this->correoRepresentante}');";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    //modificar una empresa
    public function modificar()
    {
        $cadenaSQL = "UPDATE `empresas` 
                      SET nit = '{$this->nit}' ,
                      `nombre` = '{$this->nombre}', 
                      `direccion` = '{$this->direccion}', 
                      `correo` = '{$this->correo}', 
                      `telefono` = '{$this->telefono}', 
                      `nombre_representante` = '{$this->nombreRepresentante}',
                      `correo_representante` = '{$this->correoRepresentante}' 
                       WHERE `empresas`.`nit` = '{$this->nit}'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    //eliminar una empresa
    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM empresas WHERE id = $this->id;";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }
    public function eliminarPorId($idEmpresa)
    {
        $cadenaSQL = "DELETE FROM empresas WHERE id = $idEmpresa;";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    ////////////////////////////////////////////////////////////////////////////////////
    /* REGION mapear empresas */
    public static function getLista($filtro, $orden) //sin inner join por el momento
    {
        if ($filtro == null || $filtro == '')
            $filtro = '';
        else
            $filtro = "where $filtro";
        if ($orden == null || $orden == '')
            $orden = '';
        else
            $orden = "order by $orden";

        $cadenaSQL = "SELECT id, nit, nombre, direccion, correo, telefono, nombre_representante, correo_representante 
                      FROM empresas $filtro $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = Empresa::getLista($filtro, $orden);
        $lista = array();
        for ($i = 0; $i < count($resultado); $i++) {
            $empresa = new Empresa($resultado[$i], null);
            $lista[$i] = $empresa;
        }
        return $lista;
    }

    public static function getListaEnJson($filtro, $orden)
    {
        $datos = Empresa::getListaEnObjetos($filtro, $orden);
        return json_encode($datos);
    }
}
