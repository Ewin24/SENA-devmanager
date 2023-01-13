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
    ///////////////////////////////////////////////////////////////////////
    //SECCION EMPRESAS
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
    ///////////////////////////////////////////////////////////////////////
    //SECCION USUARIOS/ TRABAJADORES / EMPLEADOS
    public static function guardarObjEmpleado($empleado)
    {
        $clave = Usuario::hash($empleado->identificacion); //genera una clave hash con la clave enviada por el usuario
        $UUID = Usuario::guidv4(); //genera el UUID
        $cadenaSQL = "
        INSERT INTO usuarios
        (id, identificacion, tipo_identificacion, nombres, apellidos, correo, clave_hash, direccion, nombre_foto, telefono, tipo_usuario, id_empresa)
        VALUES ('$UUID', 
        '$empleado->identificacion', 
        '$empleado->tipo_identificacion', 
        '$empleado->nombres',
        '$empleado->apellidos',
        '$empleado->correo', 
        '$clave', 
        '$empleado->direccion', 
        '$empleado->nombre_foto', 
        '$empleado->telefono', 
        '$empleado->tipo_usuario', 
        '$empleado->id_empresa')";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function modificarObjEmpleado($empleado)
    {
        $cadenaSQL = "
        UPDATE  usuarios
        SET     identificacion='$empleado->identificacion', 
                tipo_identificacion='$empleado->tipo_identificacion', 
                nombres='$empleado->nombres', 
                apellidos='$empleado->apellidos', 
                correo='$empleado->correo', 
                direccion='$empleado->direccion', 
                nombre_foto='$empleado->nombre_foto', 
                telefono='$empleado->telefono', 
                tipo_usuario='$empleado->tipo_usuario', 
                id_empresa='$empleado->id_empresa'
        WHERE id='$empleado->id'";
        ConectorBD::ejecutarQuery($cadenaSQL);
        //clave_hash='$this->clave_hash',  *por el momento no esta implementado cambiar la clave* 
    }

    public static function eliminarObjEmpleado($id)
    { // hace eliminación de usuario con un id especifico
        $cadenaSQL = "DELETE FROM usuarios WHERE id='$id'";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }
}
