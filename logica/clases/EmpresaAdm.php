<?php

class TrabEmpresa {

    public $identificacion;
    public $nombre; //nombre real del usuario
    public $apellido;
    public $tipoUsuario;
    public $clave;
    public $correo;
    public $telefono;
    public $tipoIdentificacion;
    public $foto;
    public $direccion;
    public $nitEmpresa;

    //constructor con array
    public function __construct($campo, $valor) {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "  SELECT  id, identificacion, tipo_identificacion, nombres, apellidos, correo, clave_hash, direccion, nombre_foto, telefono, tipo_usuario, id_empresa
                                FROM    usuarios
                                WHERE $campo = $valor;";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
                print_r($campo);
            }
            //datos usuario
            $this->id = $campo['id'];
            $this->identificacion = $campo['identificacion'];
            $this->tipoIdentificacion = $campo['tipo_identificacion'];
            $this->nombres = $campo['nombres'];
            $this->apellidos = $campo['apellidos'];
            $this->correo = $campo['correo'];
            $this->clave = $campo['clave_hash'];
            $this->direccion = $campo['direccion'];
            $this->telefono = $campo['telefono'];
            $this->tipoUsuario = $campo['tipo_usuario'];
            $this->nitEmpresa = $campo['id_empresa'];
        }
    }
}

class EmpresaAdm {    ////////////////////////////////////////////////////////////////////////////////////
    /* REGION lógica negocio para administrar empresas */

    public static function getDatosJson($filtro, $orden, $Opcion = "", $nitEmpresa = "") {

        $datos = array();

        switch ($Opcion) {

            case "TrabEmpresa":
                $filtroTrabajadores = "id_empresa = '$nitEmpresa'"; //lo que en el usuario se llama id empresa, es en realidad el nit, hay que modificar el dise�o logico
                $cadenaSQL = "  SELECT 	id, identificacion, tipo_identificacion, nombres, apellidos, correo, clave_hash, direccion, nombre_foto, telefono, tipo_usuario, id_empresa
                                FROM 	usuarios
                                WHERE 	$filtroTrabajadores $orden";
                $resultado = ConectorBD::ejecutarQuery($cadenaSQL);
                for ($i = 0; $i < count($resultado); $i++) {
                    $trabajadores = new TrabEmpresa($resultado[$i], null);
                    $datos[$i] = $trabajadores;
                }
                break;

            default:
        }
        return $datos;
    }

    public static function getTrabajadoresEmpresa($nitEmpresa) {
        //la empresa que se selecciona es pasada por parametro
        return EmpresaAdm::getDatosJson(null, null, "TrabEmpresa", $nitEmpresa);
    }

    public static function cargarTablasHijas($nitEmpresa) {

        if ($nitEmpresa != null || $nitEmpresa != '') {
            // Definiendo la lógica de negocio dentro de la clase
            $datTrabEmpresa = EmpresaAdm::getTrabajadoresEmpresa($nitEmpresa);
        }
        return [$datTrabEmpresa];
    }

}
