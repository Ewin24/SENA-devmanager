<?php

class Usuario
{

    public $id;
    public $identificacion;
    public $nombres; //nombre real del usuario
    public $apellidos;
    public $tipo_usuario;
    public $clave_hash;
    public $correo;
    public $telefono;
    public $tipo_identificacion;
    public $nombre_foto;
    public $direccion;
    public $id_empresa;

    //constructor con array
    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "  SELECT  id, identificacion, tipo_identificacion, nombres, apellidos, correo, clave_hash, direccion, nombre_foto, telefono, tipo_usuario, id_empresa
                                FROM    usuarios
                                WHERE $campo = $valor;";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
                // print_r($campo);
            }
            //datos usuario
            $this->id = $campo['id'];
            $this->identificacion = $campo['identificacion'];
            $this->tipo_identificacion = $campo['tipo_identificacion'];
            $this->nombres = $campo['nombres'];
            $this->apellidos = $campo['apellidos'];
            $this->correo = $campo['correo'];
            $this->clave_hash = $campo['clave_hash'];
            $this->direccion = $campo['direccion'];
            $this->telefono = $campo['telefono'];
            $this->tipo_usuario = $campo['tipo_usuario'];
            $this->id_empresa = $campo['id_empresa'];
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdentificacion()
    {
        return $this->identificacion;
    }

    public function getNombres()
    {
        return $this->nombres;
    }

    public function getApellidos()
    {
        return $this->apellidos;
    }

    public function getTipo_usuario()
    {
        return $this->tipo_usuario;
    }

    public function getClave_hash()
    {
        return $this->clave_hash;
    }

    public function getCorreo()
    {
        return $this->correo;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function getTipo_identificacion()
    {
        return $this->tipo_identificacion;
    }

    public function getNombre_foto()
    {
        return $this->nombre_foto;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function getId_empresa()
    {
        return $this->id_empresa;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setIdentificacion($identificacion): void
    {
        $this->identificacion = $identificacion;
    }

    public function setNombres($nombres): void
    {
        $this->nombres = $nombres;
    }

    public function setApellidos($apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    public function setTipo_usuario($tipo_usuario): void
    {
        $this->tipo_usuario = $tipo_usuario;
    }

    public function setClave_hash($clave_hash): void
    {
        $this->clave_hash = $clave_hash;
    }

    public function setCorreo($correo): void
    {
        $this->correo = $correo;
    }

    public function setTelefono($telefono): void
    {
        $this->telefono = $telefono;
    }

    public function setTipo_identificacion($tipo_identificacion): void
    {
        $this->tipo_identificacion = $tipo_identificacion;
    }

    public function setNombre_foto($nombre_foto): void
    {
        $this->nombre_foto = $nombre_foto;
    }

    public function setDireccion($direccion): void
    {
        $this->direccion = $direccion;
    }

    public function setId_empresa($id_empresa): void
    {
        $this->id_empresa = $id_empresa;
    }

    public function getTipoEnObjeto()
    {
        $tipoUsuario = new TipoUsuario($this->tipo_usuario);
        return $tipoUsuario;
    }

    //metodo para registrar un usuario en la base de datos
    public function guardar()
    {
        $cadenaSQL = "
        INSERT INTO usuarios
        (id, identificacion, tipo_identificacion, nombres, apellidos, correo, clave_hash, direccion, nombre_foto, telefono, tipo_usuario, id_empresa)
        VALUES ('$this->v4_UUID()', $this->identificacion', '$this->tipo_identificacion', '$this->nombres', '$this->apellidos', '$this->correo', md5('$this->clave_hash'), '$this->direccion', '$this->nombre_foto', '$this->telefono', '$this->tipo_usuario', '$this->id_empresa')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar()
    {
        if (strlen($this->clave_hash) < 32) {
            $this->clave_hash = md5($this->clave_hash); // si engresa clave, vendra sin una encriptacion, por ello antes de ingresarla se encripta
        }
        $cadenaSQL = "
        UPDATE  usuarios
        SET     identificacion='$this->identificacion', 
                tipo_identificacion='$this->tipo_identificacion', 
                nombres='$this->nombres', 
                apellidos='$this->apellidos', 
                correo='$this->correo', 
                clave_hash='$this->clave_hash', 
                direccion='$this->direccion', 
                nombre_foto='$this->nombre_foto', 
                telefono='$this->telefono', 
                tipo_usuario='$this->tipo_usuario', 
                id_empresa='$this->id_empresa'
        WHERE id='$this->id'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminarID($identificacionAnterior)
    { // hace eliminación de usuario con un id especifico
        $cadenaSQL = "DELETE FROM usuarios WHERE identificacion='$identificacionAnterior'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM usuarios WHERE identificacion = $this->identificacion;";
        ConectorBD::ejecutarQuery($cadenaSQL);
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

        $cadenaSQL = "  SELECT  id, identificacion, tipo_identificacion, nombres, apellidos, correo, clave_hash, direccion, nombre_foto, telefono, tipo_usuario, id_empresa
                        FROM    usuarios
                        $filtro 
                        $orden";
        // echo $cadenaSQL;
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = Usuario::getLista($filtro, $orden);

        $lista = array();

        for ($i = 0; $i < count($resultado); $i++) {
            $usuario = new Usuario($resultado[$i], null);
            $lista[$i] = $usuario;
        }
        // print_r($lista);
        return $lista;
    }

    public static function getListaEnJson($filtro, $orden)
    {
        $datos = Usuario::getListaEnObjetos($filtro, $orden);

        $json_data = array(
            //"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal" => intval(count($datos)), // total number of records
            // "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $datos   // total data array
        );

        return json_encode($json_data);  // send data as json format
    }

    public static function validar($usuario, $clave)
    {
        print_r($usuario, $clave);
        $resultado = Usuario::getListaEnObjetos("identificacion = '$usuario' AND clave_hash = md5('$clave')", null);
        $usuario = null;
        if (count($resultado) > 0) {
            $usuario = $resultado[0];
        }
        return $usuario;
    }

    private static function obtenerTipoUsuario($identificacion)
    {
        $cadenaSQL = "SELECT tipo_usuario FROM usuarios WHERE identificacion = $identificacion";
        $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
        return $resultado;
    }

    public static function esAdmin($identificacion)
    {
        print_r($identificacion);
        $resultado = Usuario::obtenerTipoUsuario($identificacion);
        if ($resultado[0]['tipo_usuario'] == 'A') { //regresa de la base de datos como arreglo
            return true;
        }
    }

    public static function esDirector($identificacion)
    {
        $resultado = Usuario::obtenerTipoUsuario($identificacion);
        if ($resultado[0]['tipo_usuario'] == 'D') { //regresa de la base de datos como arreglo
            return true;
        }
    }

    public static function getProyectosUsuario($idUsuario)
    {
        $cadenaSQL = "  SELECT      p.id, p.nombre, p.descripcion, p.estado, p.fecha_inicio, p.fecha_fin, p.id_usuario, u.correo
                        FROM 		(proyectos p
                        INNER JOIN 	usuarios u ON u.id = p.id_usuario
                        INNER JOIN 	rh_proyectos rp ON rp.id_proyecto = p.id AND rp.id_usuario = '$idUsuario')";
        // echo $cadenaSQL;
        $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
        $datos = array();
        for ($i = 0; $i < count($resultado); $i++) {
            $datos[$i] = new Proyecto($resultado[$i], null);
        }
        // return json_encode($datos);
        return $datos;
    }
}
