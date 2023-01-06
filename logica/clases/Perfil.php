<?php

//ahora esta clase tiene la finalidad de traer todos los datos que tine un usuario, en sus habilidades, proyectos y estudios
class Perfil
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

    private $estudios;
    private $habilidades;

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
            $this->nombre_foto = $campo['nombre_foto'];
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

    public function getTipoUsuario()
    {
        return $this->tipo_usuario;
    }

    public function getClave()
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

    public function getTipoIdentificacion()
    {
        return $this->tipo_identificacion;
    }

    public function getFoto()
    {
        return $this->nombre_foto;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function getIdEmpresa()
    {
        return $this->id_empresa;
    }

    // public function guardar()
    // {
    //     //echo $this->nombre, $this->descripcion;
    //     $cadenaSQL = "INSERT INTO  perfil (nombre, descripcion ) VALUES ('$this->nombre', '$this->descripcion')";
    //     ConectorBD::ejecutarQuery($cadenaSQL);
    // }

    // public function modificar()
    // {
    //     $cadenaSQL = "update perfil set nombre='{$this->nombre}', descripcion='{$this->descripcion}' where idPerfil= {$this->idPerfil}";
    //     ConectorBD::ejecutarQuery($cadenaSQL);
    // }

    // public function eliminar()
    // {
    //     $cadenaSQL = "DELETE FROM perfil WHERE idPerfil = $this->idPerfil;";
    //     ConectorBD::ejecutarQuery($cadenaSQL);
    // }

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

        $cadenaSQL = "  SELECT  id, identificacion, tipo_identificacion, nombres, apellidos, correo, clave_hash, direccion, nombre_foto, telefono, tipo_usuario, id_empresa
                            FROM    usuarios
                            $filtro 
                            $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = Perfil::getLista($filtro, $orden);
        $lista = array();
        for ($i = 0; $i < count($resultado); $i++) {
            $perfil = new Perfil($resultado[$i], null);
            $lista[$i] = $perfil;
        }
        return $lista;
    }

    public function getIdPerfil($campo, $valor)
    {
        $cadenaSQL = "SELECT idPerfil FROM perfil WHERE $campo = '$valor';";
        $id = ConectorBD::ejecutarQuery($cadenaSQL);
        $this->id = $id;
        return $this->id;
    }
}
