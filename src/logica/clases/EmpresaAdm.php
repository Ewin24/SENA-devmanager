<?php
//require_once '../clasesGenericas/ConectorBD.php';
class TrabEmpresa
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
                print_r($campo);
            }
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
}

class EmpresaAdm
{    ////////////////////////////////////////////////////////////////////////////////////
    /* REGION lógica negocio para administrar empresas */

    public static function getDatosJson($filtro, $orden, $Opcion = "", $idEmpresa = "")
    {

        $datos = array();

        switch ($Opcion) {

            case "TrabEmpresa":
                $filtroTrabajadores = "id_empresa = '$idEmpresa'"; //lo que en el usuario se llama id empresa, es en realidad el nit, hay que modificar el dise�o logico
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

    public static function getTrabajadoresEmpresa($idEmpresa)
    {
        //la empresa que se selecciona es pasada por parametro
        return EmpresaAdm::getDatosJson(null, null, "TrabEmpresa", $idEmpresa);
    }

    public static function cargarTablasHijas($nitEmpresa)
    {

        if ($nitEmpresa != null || $nitEmpresa != '') {
            // Definiendo la lógica de negocio dentro de la clase
            $datTrabEmpresa = EmpresaAdm::getTrabajadoresEmpresa($nitEmpresa);
        }
        return [$datTrabEmpresa];
    }

    public static function guardarObj($empresa)
    {
        $cadenaSQL = "INSERT INTO empresas(id, nit, nombre, direccion, correo, telefono, nombre_representante, correo_representante) 
                      VALUES (  '{$empresa->id}', 
                                '{$empresa->nit}', 
                                '{$empresa->nombre}', 
                                '{$empresa->direccion}', 
                                '{$empresa->correo}', 
                                '{$empresa->telefono}', 
                                '{$empresa->nombreRepresentante}', 
                                '{$empresa->correoRepresentante}');";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function modificarObj($empresa)
    {
        $cadenaSQL = "UPDATE `empresas` 
                      SET nit = '{$empresa->nit}' ,
                      `nombre` = '{$empresa->nombre}', 
                      `direccion` = '{$empresa->direccion}', 
                      `correo` = '{$empresa->correo}', 
                      `telefono` = '{$empresa->telefono}', 
                      `nombre_representante` = '{$empresa->nombreRepresentante}',
                      `correo_representante` = '{$empresa->correoRepresentante}' 
                       WHERE `empresas`.`nit` = '{$empresa->nit}'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function eliminarObj($idEmpresa)
    {
        $cadenaSQL = "DELETE FROM empresas WHERE id = '$idEmpresa'";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }
}