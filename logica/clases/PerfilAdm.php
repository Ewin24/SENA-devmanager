<?php

class EstudiosAdm
{
    // usuarios_estudios 
    // SELECT id, nombre_certificado, nombre_archivo, fecha_certificado, id_usuario, id_estudio
    // FROM usuarios_estudios;
    public $id;
    public $nombre_certificado;
    public $nombre_archivo;
    public $fecha_certificado;
    public $id_usuario;
    public $id_estudio;

    //constructor con array
    public function __construct($campo, $valor) {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "  SELECT id, nombre_certificado, nombre_archivo, fecha_certificado, id_usuario, id_estudio
                                FROM usuarios_estudios
                                WHERE $campo = $valor;";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
                print_r($campo);
            }
            //datos usuario
            $this-> id = $campo['id'];
            $this-> nombre_certificado = $campo['nombre_certificado'];
            $this-> nombre_archivo = $campo['nombre_archivo'];
            $this-> fecha_certificado = $campo['fecha_certificado'];
            $this-> id_usuario = $campo['id_usuario'];
            $this-> id_estudio = $campo['id_estudio'];
        }
    }

    private function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '')
            $filtro = '';
        else
            $filtro = "WHERE $filtro";
        if ($orden == null || $orden == '')
            $orden = '';
        else
            $orden = "ORDER BY $orden";

        $cadenaSQL = "  SELECT id, nombre_certificado, nombre_archivo, fecha_certificado, id_usuario, id_estudio
                        FROM usuarios_estudios
                        WHERE $filtro $orden;";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    protected function getListaEnJson($filtro, $orden)
    {
        $resultado = Usuario::getLista($filtro, $orden);
        $datos = array();
        for ($i = 0; $i < count($resultado); $i++) {
            $usuario = new Usuario($resultado[$i], null);
            $datos[$i] = $usuario;
        }
        return json_encode($datos);
    }

    public static function getEstudiosUsuario($idUsuario){
        $filtroEstudiosUsuario = 'id=$idUsuario';
        return EstudiosAdm::getListaEnJson($filtroEstudiosUsuario, null);
    }
}

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
    public function __construct($campo, $valor) {
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

    private function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '')
            $filtro = '';
        else
            $filtro = "WHERE $filtro";
        if ($orden == null || $orden == '')
            $orden = '';
        else
            $orden = "ORDER BY $orden";

        $cadenaSQL = "  SELECT   id, experiencia, id_usuario, id_habilidad
                        FROM     usuarios_habilidades
                        WHERE   $filtro $orden;";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    protected function getListaEnJson($filtro, $orden)
    {
        $resultado = Usuario::getLista($filtro, $orden);
        $datos = array();
        for ($i = 0; $i < count($resultado); $i++) {
            $usuario = new Usuario($resultado[$i], null);
            $datos[$i] = $usuario;
        }
        return json_encode($datos);
    }

    public static function getHabilidadesUsuario($idUsuario){
        $filtroHabilidadesUsuario = 'id=$idUsuario';
        return HabilidadesAdm::getListaEnJson($filtroHabilidadesUsuario, null);
    }
}