<?php

class HabilidadesAdm
{
    // usuarios_habilidades 
    // SELECT   id, experiencia, id_usuario, id_habilidad
    // FROM     usuarios_habilidades;
    public $id;
    public $experiencia;
    public $id_usuario;
    public $id_habilidad;

    //constructor con array
    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "  SELECT   id, experiencia, id_usuario, id_habilidad
                                FROM     usuarios_habilidades
                                WHERE $campo = $valor;";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
                print_r($campo);
            }
            //datos usuario
            $this->id = $campo['id'];
            $this->experiencia = $campo['experiencia'];
            $this->id_usuario = $campo['id_usuario'];
            $this->id_habilidad = $campo['id_habilidad'];
        }
    }
}
class EstudiosAdm
{
    public $id;
    public $nombre_certificado;
    public $nombre_archivo;
    public $fecha_certificado;
    public $id_usuario;
    public $id_estudio;

    //constructor con array
    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "  SELECT id, nombre_certificado, nombre_archivo, fecha_certificado, id_usuario, id_estudio
                                FROM usuarios_estudios
                                WHERE $campo = $valor;";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
                print_r($campo);
            }
            //datos usuario
            $this->id = $campo['id'];
            $this->nombre_certificado = $campo['nombre_certificado'];
            $this->nombre_archivo = $campo['nombre_archivo'];
            $this->fecha_certificado = $campo['fecha_certificado'];
            $this->id_usuario = $campo['id_usuario'];
            $this->id_estudio = $campo['id_estudio'];
        }
    }
}

class PerfilAdm //encargada de hacer las consultas y devolver datos en json
{
    public static function getDatosJson($filtro, $orden, $Opcion = "", $idUsuario = "")
    {
        $datos = array();
        switch ($Opcion) {
            case 'TrabEstudio': //habilidades trabajador
                $filtroEst = "id_usuario = '$idUsuario'";
                $cadenaSQL = "  SELECT id, nombre_certificado, nombre_archivo, fecha_certificado, id_usuario, id_estudio
                                FROM usuarios_estudios
                                WHERE $filtroEst $orden;";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
                for ($i = 0; $i < count($resultado); $i++) {
                    $estudios = new EstudiosAdm($resultado[$i], null);
                    $datos[$i] = $estudios;
                }
                break;
            case 'TrabHabilidades': //estudios trabajador
                $filtroHab = "id_usuario = '$idUsuario'";
                $cadenaSQL = "  SELECT   id, experiencia, id_usuario, id_habilidad
                                FROM     usuarios_habilidades
                                WHERE   $filtroHab $orden;";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
                for ($i = 0; $i < count($resultado); $i++) {
                    $habilidades = new HabilidadesAdm($resultado[$i], null);
                    $datos[$i] = $habilidades;
                }
                break;
            default:
                # code...
                break;
        }
        return $datos;
    }

    public static function getHabTrabajador($idUsuario){
        return PerfilAdm::getDatosJson(null,null, 'TrabHabilidades', $idUsuario);
    }

    public static function getEstTrabajador($idUsuario){
        return PerfilAdm::getDatosJson(null,null, 'TrabEstudio', $idUsuario);
    }

    public static function cargarTablasHijas($idUsuario) {
        if ($idUsuario != null || $idUsuario != '') {
            // Definiendo la lógica de negocio dentro de la clase
            $datHabTrabajador = PerfilAdm::getHabTrabajador($idUsuario);
            $datEstTrabajador = PerfilAdm::getEstTrabajador($idUsuario);
        }
        return [$datEstTrabajador , $datHabTrabajador]; //mandar en el orden en que se reciben en el controlador
    }

    public static function guardarObj($usuario)
    {
        $clave = Usuario::hash($usuario->identificacion); //a clave es la identificacion encriptada
        $UUID = Usuario::guidv4(); //genera el UUID
        $cadenaSQL = "INSERT INTO usuarios
                            (id, identificacion, tipo_identificacion, nombres, apellidos, correo, clave_hash, direccion, nombre_foto, telefono, tipo_usuario, id_empresa)
                            VALUES ('$UUID',
                             '$usuario->identificacion', 
                             '$usuario->tipo_identificacion', 
                             '$usuario->nombres', 
                             '$usuario->apellidos', 
                             '$usuario->correo', 
                             '$clave', 
                             '$usuario->direccion', 
                             '$usuario->nombre_foto', 
                             '$usuario->telefono', 
                             '$usuario->tipo_usuario', 
                             '$usuario->id_empresa')";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    //modificar usuario
    public static function modificarObj($usuario)
    {
        $cadenaSQL = "UPDATE  usuarios
                      SET   identificacion='$usuario->identificacion', 
                            tipo_identificacion='$usuario->tipo_identificacion', 
                            nombres='$usuario->nombres', 
                            apellidos='$usuario->apellidos', 
                            correo='$usuario->correo', 
                            direccion='$usuario->direccion', 
                            nombre_foto='$usuario->nombre_foto', 
                            telefono='$usuario->telefono', 
                            tipo_usuario='$usuario->tipo_usuario', 
                            id_empresa='$usuario->id_empresa'
                       WHERE id='$usuario->id'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
        //clave_hash='$this->clave_hash',  *por el momento no esta implementado cambiar la clave* 
    }

    public static function eliminarObj($id)
    { // hace eliminación de usuario con un id especifico
        $cadenaSQL = "DELETE FROM usuarios WHERE identificacion='$id'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

}


