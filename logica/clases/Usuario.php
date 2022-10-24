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
                $cadenaSQL = "select identificacion, nombre, apellido, tipoUsuario, clave, nombreUsuario, correo, "
                    . "telefono, tipoIdentificacion, foto, direccion, nitEmpresa_FK from usuario where $campo = $valor";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
                print_r($campo);
            }
            //asignacion de los datos
            $this->identificacion = $campo['identificacion'];
            $this->nombre = $campo['nombre'];
            $this->apellido = $campo['apellido'];
            $this->tipoUsuario = 'T'; //por defecto es trabajador ,tambien puede ser $campo['tipoUsuario']
            $this->clave = $campo['clave'];
            $this->nombreUsuario = $campo['identificacion']; //por defecto el nombre de usuario es la identificacion
            $this->correo = $campo['correo'];
            $this->telefono = $campo['telefono'];
            $this->tipoIdentificacion = $campo['tipoIdentificacion'];
            $this->foto = $campo['foto'];
            $this->direccion = $campo['direccion'];
            $this->nitEmpresa = $campo['nitEmpresa_FK']; //la idea es que las empresas ya creadas se muestren como opcion en el formulario de registro
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
        $cadenaSQL = "insert into usuario (identificacion,nombre,apellido,tipoUsuario,clave, nombreUsuario, correo, telefono, tipoIdentificacion, foto, direccion, nitEmpresa_FK) values ('$this->identificacion', '$this->nombre', '$this->apellido', '$this->tipoUsuario', md5('$this->clave'), '$this->nombreUsuario', '$this->correo', '$this->telefono', '$this->tipoIdentificacion', '$this->foto', '$this->direccion', '$this->nitEmpresa')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar($identificacionAnterior)
    {
        if (strlen($this->clave) < 32) {
            $this->clave = md5($this->clave); // si engresa clave, vendra sin una encriptacion, por ello antes de ingresarla se encripta
        }
        $cadenaSQL = "update usuario set identificacion = '$this->identificacion', nombre = '$this->nombre', apellido = '$this->apellido', tipoUsuario = '$this->tipoUsuario', clave = '$this->clave', nombreUsuario = '$this->nombreUsuario', correo = '$this->correo', telefono = '$this->telefono', tipoIdentificacion = '$this->tipoIdentificacion', foto = '$this->foto', direccion = '$this->direccion', nitEmpresa_FK = '$this->nitEmpresa' where identificacion = '$identificacionAnterior'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminarID($identificacionAnterior)
    { // hace eliminacion de usuario con un id especifico
        $cadenaSQL = "delete from usuario where identificacion = '$identificacionAnterior'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminar()
    {
        $cadenaSQL = "delete from usuario where identificacion = $this->identificacion;";
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

        $cadenaSQL = "select identificacion, nombre, apellido, tipoUsuario, clave, nombreUsuario, correo, "
            . "telefono, tipoIdentificacion, foto, direccion, nitEmpresa_FK from usuario $filtro $orden";
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
        $resultado = Usuario::getListaEnObjetos("identificacion = '$usuario' and clave = md5('$clave')", null);
        $usuario = null;
        if (count($resultado) > 0) {
            $usuario = $resultado[0];
        }
        return $usuario;
    }

    public static function esAdmin($identificacion)
    {
        $cadenaSQL = "SELECT tipoUsuario FROM `usuario` WHERE identificacion = $identificacion";
        $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
        if ($resultado === 'A') {
            return true;
        }
    }
}
