<?php

class Usuario
{

    protected $identificacion;
    protected $nombre; //nombre real del usuario
    protected $apellido;
    protected $tipoUsuario;
    protected $clave;
    protected $nombreUsuario; //nombre que se usa para identificar al usuario y darle ingreso al sistema, por defecto N.identificacion
    protected $correo;
    protected $telefono;
    protected $tipoIdentificacion;
    protected $foto;
    protected $direccion;
    protected $nitEmpresa;

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "
                SELECT  id,
                identificacion, tipo_identificacion, nombre_usuario, nombres, apellidos, correo, 
                clave_hash, direccion, nombre_foto, telefono, tipo_usuario, id_empresa
                FROM usuarios
                WHERE $campo = $valor";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
                print_r($campo);
            }
            //asignacion de los datos
            $this->identificacion = $campo['identificacion'];
            $this->nombre = $campo['nombre'];
            $this->apellido = $campo['apellido'];
            $this->tipoUsuario = $campo['tipo_usuario']; //por defecto es trabajador ,tambien puede ser $campo['tipoUsuario']
            $this->clave = $campo['clave_hash'];
            $this->nombreUsuario = $campo['identificacion']; //por defecto el nombre de usuario es la identificacion
            $this->correo = $campo['correo'];
            $this->telefono = $campo['telefono'];
            $this->tipoIdentificacion = $campo['tipo_identificacion'];
            $this->foto = $campo['nombre_foto'];
            $this->direccion = $campo['direccion'];
            $this->nitEmpresa = $campo['id_empresa']; //la idea es que las empresas ya creadas se muestren como opcion en el formulario de registro
        }
    }

    public function getIdentificacion()
    {
        return $this->identificacion;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function getTipoUsuario()
    {
        return $this->tipoUsuario;
    }

    public function getClave()
    {
        return $this->clave;
    }

    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    public function getCorreo()
    {
        return $this->correo;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function getTipoIdentificacion()
    {
        return $this->tipoIdentificacion;
    }

    public function getFoto()
    {
        return $this->foto;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function getNitEmpresa()
    {
        return $this->nitEmpresa;
    }

    public function setIdentificacion($identificacion): void
    {
        $this->identificacion = $identificacion;
    }

    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido): void
    {
        $this->apellido = $apellido;
    }

    public function setTipoUsuario($tipoUsuario): void
    {
        $this->tipoUsuario = $tipoUsuario;
    }

    public function setClave($clave): void
    {
        $this->clave = $clave;
    }

    public function setNombreUsuario($nombreUsuario): void
    {
        $this->nombreUsuario = $nombreUsuario;
    }

    public function setCorreo($correo): void
    {
        $this->correo = $correo;
    }

    public function setTelefono($telefono): void
    {
        $this->telefono = $telefono;
    }

    public function setTipoIdentificacion($tipoIdentificacion): void
    {
        $this->tipoIdentificacion = $tipoIdentificacion;
    }

    public function setFoto($foto): void
    {
        $this->foto = $foto;
    }

    public function setDireccion($direccion): void
    {
        $this->direccion = $direccion;
    }

    public function setNitEmpresa($nitEmpresa): void
    {
        $this->nitEmpresa = $nitEmpresa;
    }

    public function getTipoEnObjeto()
    {
        $tipoUsuario = new TipoUsuario($this->tipoUsuario);
        return $tipoUsuario;
    }

    public function __toString()
    {
        $cadena = "identificacion: $this->identificacion <br>nombre : $this->nombre  <br>apellido: $this->apellido";
        return $cadena;
    }

    //metodo para registrar un usuario en la base de datos
    public function guardar()
    {
        $cadenaSQL = "
        INSERT INTO usuarios
        (id, identificacion, tipo_identificacion, nombres, apellidos, correo, clave_hash, direccion, nombre_foto, telefono, tipo_usuario, id_empresa)
        VALUES ('$this->v4_UUID()', $this->identificacion', '$this->tipoIdentificacion', '$this->nombre', '$this->apellido', '$this->correo', md5('$this->clave'), '$this->direccion', '$this->foto', '$this->telefono', '$this->tipoUsuario', '$this->nitEmpresa')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($identificacionAnterior)
    {
        if (strlen($this->clave) < 32) {
            $this->clave = md5($this->clave); // si engresa clave, vendra sin una encriptacion, por ello antes de ingresarla se encripta
        }
        $cadenaSQL = "
        UPDATE  usuarios
        SET     identificacion='$this->identificacion', 
                tipo_identificacion='$this->tipoIdentificacion', 
                nombres='$this->nombre', 
                apellidos='$this->apellido', 
                correo='$this->correo', 
                clave_hash='$this->clave', 
                direccion='$this->direccion', 
                nombre_foto='$this->foto', 
                telefono='$this->telefono', 
                tipo_usuario='$this->tipoUsuario', 
                id_empresa='$this->nitEmpresa'
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

        $cadenaSQL = "SELECT identificacion, nombres, apellidos, tipo_usuario, clave_hash, correo, telefono, tipo_identificacion, nombre_foto, direccion, id_empresa
                      FROM usuarios 
                      $filtro 
                      $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = Usuario::getLista($filtro, $orden);
        $lista = array();
        for ($i = 0; $i < count($resultado); $i++) {
            $persona = new Usuario($resultado[$i], null);
            $lista[$i] = $persona;
        }
        return $lista;
    }

    public static function validar($usuario, $clave)
    {
        $resultado = Usuario::getListaEnObjetos("identificacion = '$usuario' AND clave_hash = md5('$clave')", null);
        $usuario = null;
        if (count($resultado) > 0) {
            $usuario = $resultado[0];
        }
        return $usuario;
    }

    private static function obtenerTipoUsuario($identificacion){
        $cadenaSQL = "SELECT tipo_usuario FROM usuarios WHERE identificacion = $identificacion";
        $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
        return $resultado;
    }

    public static function esAdmin($identificacion)
    {
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
}
