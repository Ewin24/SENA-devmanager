<?php

class HabilidadesAdm
{
    //SELECT nombre, descripcion, experiencia 
    //FROM usuarios_habilidades 
    //INNER JOIN habilidades ON usuarios_habilidades.id_habilidad = habilidades.id 
    //WHERE id_usuario = 'eb036f8a-75bd-4811-a477-1444e2521f3b';

    //public $id; //id de usuario_habilidades
    public $id_habilidad;
    public $experiencia;
    public $id_usuario;
    public $nombre; //nombre de la habilidad
    public $descripcion; //descripcion de la habilidad

    //constructor con array
    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "  SELECT id_habilidad, id_usuario, nombre, descripcion, experiencia 
                                FROM usuarios_habilidades 
                                INNER JOIN habilidades ON usuarios_habilidades.id_habilidad = habilidades.id 
                                WHERE $campo = $valor;";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
                print_r($campo);
            }
            //$this->id = $campo['id'];
            $this->id_habilidad = $campo['id_habilidad'];
            $this->id_usuario = $campo['id_usuario'];
            $this->nombre = $campo['nombre'];
            $this->descripcion = $campo['descripcion'];
            $this->experiencia = $campo['experiencia'];
        }
    }
}
class EstudiosAdm
{
    //SELECT id_usuario,id_estudio, fecha_certificado, nombre_archivo, nombre_certificado, nombre 
    // FROM usuarios_estudios 
    // INNER JOIN estudios 
    // WHERE 1 =1; 

    //public $id; //id de usuario_estudios
    public $nombre; //nombre del estudio
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
                $cadenaSQL = "  SELECT  nombre, fecha_certificado, nombre_archivo, nombre_certificado, id_usuario, id_estudio
                                FROM usuarios_estudios 
                                INNER JOIN estudios
                                WHERE $campo = $valor;";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
                print_r($campo);
            }
            //$this->id = $campo['id'];
            $this->nombre = $campo['nombre'];
            $this->fecha_certificado = $campo['fecha_certificado'];
            $this->nombre_archivo = $campo['nombre_archivo'];
            $this->nombre_certificado = $campo['nombre_certificado'];
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
                //SELECT id_usuario,id_estudio, fecha_certificado, nombre_archivo, nombre_certificado, nombre 
                // FROM usuarios_estudios 
                // INNER JOIN estudios 
                // WHERE id_usuario = 'eb036f8a-75bd-4811-a477-1444e2521f3b'; 
                $filtroEst = "id_usuario = '$idUsuario'";
                $cadenaSQL = "  SELECT      ue.id, es.nombre, ue.fecha_certificado, ue.nombre_archivo, ue.nombre_certificado, ue.id_usuario, ue.id_estudio
                                FROM        usuarios_estudios ue
                                INNER JOIN  estudios es on ue.id_estudio =  es.id
                                WHERE $filtroEst $orden;";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
                for ($i = 0; $i < count($resultado); $i++) {
                    $estudios = new EstudiosAdm($resultado[$i], null);
                    $datos[$i] = $estudios;
                }
                break;

            case 'TrabHabilidades': //estudios trabajador
                //SELECT nombre, descripcion, experiencia 
                //FROM usuarios_habilidades 
                //INNER JOIN habilidades ON usuarios_habilidades.id_habilidad = habilidades.id 
                //WHERE id_usuario = 'eb036f8a-75bd-4811-a477-1444e2521f3b';
                $filtroHab = "id_usuario = '$idUsuario'";
                $cadenaSQL = "  SELECT uh.id, uh.id_habilidad, uh.id_usuario, uh.experiencia, h.descripcion, h.nombre
                                FROM usuarios_habilidades uh
                                INNER JOIN  habilidades h ON uh.id_habilidad = h.id 
                                WHERE $filtroHab $orden;";
                //echo $cadenaSQL;
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

    public static function getHabTrabajador($idUsuario)
    {
        return PerfilAdm::getDatosJson(null, null, 'TrabHabilidades', $idUsuario);
    }

    public static function getEstTrabajador($idUsuario)
    {
        return PerfilAdm::getDatosJson(null, null, 'TrabEstudio', $idUsuario);
    }

    public static function cargarTablasHijas($idUsuario)
    {
        if ($idUsuario != null || $idUsuario != '') {
            // Definiendo la lógica de negocio dentro de la clase
            $datHabTrabajador = PerfilAdm::getHabTrabajador($idUsuario);
            $datEstTrabajador = PerfilAdm::getEstTrabajador($idUsuario);
        }
        return [$datEstTrabajador, $datHabTrabajador]; //mandar en el orden en que se reciben en el controlador
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
        $cadenaSQL = "DELETE FROM usuarios WHERE id='$id'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }
}
