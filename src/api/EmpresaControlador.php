<?php

require_once '../logica/clasesGenericas/ConectorBD.php';
require_once '../logica/clasesGenericas/ddl_parametrizado.php';
require_once '../logica/clases/EmpresaAdm.php';
require_once '../logica/clases/Usuario.php';
require_once '../logica/clases/Empresa.php';

if (!empty($_POST['action'])) {

    try {

        $accion = $_POST['action'];
        $response = '';
        switch ($accion) {

                //////////////////////////////////////////////////////////////////////////////////////////////////////
                //SECCION EMPRESAS
            case 'Insertar_tblEmpresas':
                header('Content-type: application/json; charset=utf-8');
                $newEmpresa = json_decode($_POST['datos']);
                if ($newEmpresa != null) {
                    $newEmpresa->id = ConectorBD::get_UUIDv4();
                    EmpresaAdm::guardarObj($newEmpresa);
                }

                $response = array(
                    "data" => $newEmpresa->id,
                    "accion" => $accion
                );
                break;

            case 'Modificar_tblEmpresas':
                header('Content-type: application/json; charset=utf-8');
                $editarEmpresa = json_decode($_POST['datos']);

                if ($editarEmpresa != null || $editarEmpresa != '') {
                    EmpresaAdm::modificarObj($editarEmpresa);
                }

                $response = array(
                    "data" => $editarEmpresa,
                    "accion" => $accion
                );
                break;

            case 'Eliminar_tblEmpresas':
                header('Content-type: application/json; charset=utf-8');
                $eliminarEmpresa = json_decode($_POST['datos']);

                if ($eliminarEmpresa != null || $eliminarEmpresa != '') {
                    EmpresaAdm::eliminarObj($eliminarEmpresa->id);
                }

                $response = array(
                    "data" => $eliminarEmpresa,
                    "accion" => $accion
                );
                break;

            case 'cargar_tblEmpresas':
                header('Content-type: application/json; charset=utf-8');
                $idUsuario = $_POST['datos'];
                $USUARIO = Usuario::getListaEnObjetos("id='$idUsuario'", null)[0];
                $tipoUsuario = $USUARIO->getTipo_usuario();

                switch ($tipoUsuario) {
                    case 'A': //Admin (Modo CRUD): muestra todos los perfiles y opciones porque es admin
                        $datosEmpresas = Empresa::getListaEnObjetos(null, null);
                        $modoTabla = 'CRUD';
                        break;

                    case 'D': //Director solo puede entrar y ver los perfiles 
                        $filtroUsuario = "id_usuario='$idUsuario'"; //en caso de que se tenga que filtrar
                        $datosEmpresas = Empresa::getListaEnObjetos(null, null);
                        $modoTabla = 'R';
                        break;

                    case 'T': //Trabajador solo lectura
                        $filtroUsuario = "id_usuario='$idUsuario'"; //en caso de que se tenga que filtrar
                        $datosEmpresas = Empresa::getListaEnObjetos(null, null);
                        $modoTabla = 'R';
                        break;

                    default: //por el momento esto seria trabajador, aunque es mejor lanzar un error si es un usuario invalido
                        // $datosEmpresas = Usuario::getProyectosUsuario($idUsuario);
                        // $modoTabla = 'R';
                        break;
                }

                $response = array(
                    "data" => $datosEmpresas,
                    "accion" => $accion,
                    "tipoUsuario" => $tipoUsuario
                );
                // $response = $datosProyectos;
                break;

                //////////////////////////////////////////////////////////////////////////////////////////////////////
                //SECCION EMPLEADOS
            case 'Insertar_tblEmpleados':
                header('Content-type: application/json; charset=utf-8');
                $newEmpleado = json_decode($_POST['datos']);
                if ($newEmpleado != null) {
                    $newEmpleado->id = ConectorBD::get_UUIDv4();
                    EmpresaAdm::guardarObjEmpleado($newEmpleado);
                }

                $response = array(
                    "data" => $newEmpleado->id,
                    "accion" => $accion
                );
                break;

            case 'Modificar_tblEmpleados':
                header('Content-type: application/json; charset=utf-8');
                $editarEmpleado = json_decode($_POST['datos']);

                if ($editarEmpleado != null || $editarEmpleado != '') {
                    EmpresaAdm::modificarObjEmpleado($editarEmpleado);
                }

                $response = array(
                    "data" => $editarEmpleado,
                    "accion" => $accion
                );
                break;

                //TODO: hace falta revisarlo en tabla generica
            case 'Eliminar_tblEmpleados':
                header('Content-type: application/json; charset=utf-8');
                $eliminarEmpleado = json_decode($_POST['datos']);
                if ($eliminarEmpleado != null || $eliminarEmpleado != '') {
                    EmpresaAdm::eliminarObj($eliminarEmpleado->id);
                }

                $response = array(
                    "data" => $eliminarEmpleado,
                    "accion" => $accion
                );
                break;

            case 'cargar_tblEmpleados':
                header('Content-type: application/json; charset=utf-8');
                $idEmpresaSeleccionada = $_POST['datos'];

                if ($idEmpresaSeleccionada != null || $idEmpresaSeleccionada != '') {
                    //// Definiendo la lógica de negocio dentro de la clase
                    $datTrabajadores = EmpresaAdm::getTrabajadoresEmpresa($idEmpresaSeleccionada);
                }

                $htmlTabla = $_POST['html_tabla'];
                $json_ddl = Ddl_Parametrizado::getddlOps("tabla= '$htmlTabla' AND campo in('tipo_identificacion', 'tipo_usuario', 'id_empresa')", null);
                $response = array(
                    "data" => $datTrabajadores,
                    "idEmpresaSeleccionada" => $idEmpresaSeleccionada,
                    "ddl_ops" => $json_ddl,
                    "accion" => $accion
                );
                break;

            default:
                $response = array(
                    "data" => array(),
                    "accion" => "Acción no definida"
                );
                break;
        }
    } catch (customException $e) {
        $response = array(
            "data" => array(),
            "accion" => "Error generado en $accion",
            "error" => $e->errorMessage()
        );
    } catch (Exception $e) {
        $response = array(
            "data" => array(),
            "accion" => "Error generado en $accion",
            "error" => $e->getMessage()
        );
    }
    // Enviando respuesta hacia el frontEnd
    echo json_encode($response);
}
