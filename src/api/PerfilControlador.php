<?php

require_once '../logica/clasesGenericas/ConectorBD.php';
require_once '../logica/clasesGenericas/ddl_parametrizado.php';
require_once '../logica/clases/PerfilAdm.php';
require_once '../logica/clases/Perfil.php';
require_once '../logica/clases/Usuario.php';
require_once '../upload.php';


if (!empty($_POST['action'])) {

    try {

        $accion = $_POST['action'];
        $response = '';
        // echo $accion;
        switch ($accion) {

                //////////////////////////////////////////////////////////////////////////////////////////////////////
                //SECCION PERFILES
            case 'Insertar_tblEmpleados':
                header('Content-type: application/json; charset=utf-8');
                $newPerfil = json_decode($_POST['datos']);
                if ($newPerfil != null) {
                    PerfilAdm::guardarObj($newPerfil);
                }

                $response = array(
                    "data" => $newPerfil->id,
                    "accion" => $accion
                );
                break;

            case 'Modificar_tblEmpleados':
                header('Content-type: application/json; charset=utf-8');
                $editarPerfil = json_decode($_POST['datos']);

                if ($editarPerfil != null || $editarPerfil != '') {
                    PerfilAdm::modificarObj($editarPerfil);
                }

                $response = array(
                    "data" => $editarPerfil,
                    "accion" => $accion
                );
                break;

            case 'Eliminar_tblEmpleados':
                header('Content-type: application/json; charset=utf-8');
                $eliminarPerfil = $_POST['datos'];
                if ($eliminarPerfil != null || $eliminarPerfil != '') {
                    PerfilAdm::eliminarObj($eliminarPerfil);
                }

                $response = array(
                    "data" => $eliminarPerfil,
                    "accion" => $accion
                );
                break;

            case 'cargar_tblEmpleados':
                header('Content-type: application/json; charset=utf-8');
                $idUsuario = $_POST['datos'];
                $USUARIO = Usuario::getListaEnObjetos("u.id='$idUsuario'", null)[0];
                $tipoUsuario = $USUARIO->getTipo_usuario();

                switch ($tipoUsuario) {
                    case 'A': //Admin (Modo CRUD): muestra todos los perfiles y opciones porque es admin
                        $datosPerfil = Perfil::getListaEnObjetos(null, null);
                        $modoTabla = 'CRUD';
                        break;

                    case 'D': //Director (modo CRUD filtrado): solo su información de perfil activo
                        $filtroUsuario = "id='$idUsuario'";
                        $datosPerfil = Perfil::getListaEnObjetos(null, null);
                        $modoTabla = 'RU';
                        break;

                    case 'T': //Director (modo CRUD filtrado): solo su información de perfil activo
                        $filtroUsuario = "id='$idUsuario'";
                        $datosPerfil = Perfil::getListaEnObjetos($filtroUsuario, null);
                        $modoTabla = 'R';
                        break;

                    default: //trabajador (modo: Solo lectura): perfiles existentes
                        // $datosProyectos = Usuario::getProyectosUsuario($idUsuario);
                        // $modoTabla = 'R';
                        break;
                }
                $htmlTabla = $_POST['html_tabla'];
                $json_ddl = Ddl_Parametrizado::getddlOps("tabla= '$htmlTabla' AND campo in('tipo_identificacion', 'tipo_usuario', 'id_empresa')", null);
                $response = array(
                    "data" => $datosPerfil,
                    "idPerfilSeleccionado" => $idUsuario,
                    "ddl_ops" => $json_ddl,
                    "tipoUsuario" => $tipoUsuario
                );

                break;

                //////////////////////////////////////////////////////////////////////////////////////////////////////
                //SECCION HABILIDADES
            case 'Insertar_tblHabilidades':
                header('Content-type: application/json; charset=utf-8');
                $newHabilidad = json_decode($_POST['datos']);


                if ($newHabilidad != null) {
                    HabilidadesAdm::guardarObj($newHabilidad);
                }
                $response = array(
                    "data" => $newHabilidad,
                    "accion" => $accion
                );
                break;

            case 'Modificar_tblHabilidades':
                header('Content-type: application/json; charset=utf-8');
                $editarHabilidad = json_decode($_POST['datos']);

                if ($editarHabilidad != null || $editarHabilidad != '') {
                    HabilidadesAdm::modificarObj($editarHabilidad);
                }

                $response = array(
                    "data" => $editarHabilidad->id,
                    "accion" => $accion
                );
                break;

            case 'Eliminar_tblHabilidades':
                header('Content-type: application/json; charset=utf-8');
                $id_habilidad = $_POST['datos'];

                if ($id_habilidad != null || $id_habilidad != '') {
                    HabilidadesAdm::eliminarObj($id_habilidad);
                }

                $response = array(
                    "data" => $id_habilidad,
                    "accion" => $accion
                );
                break;

            case 'cargar_tblHabilidades':
                header('Content-type: application/json; charset=utf-8');
                $idPerfilSeleccionado = $_POST['datos'];

                if ($idPerfilSeleccionado != null || $idPerfilSeleccionado != '') {
                    $datosHabilidades = PerfilAdm::getHabTrabajador($idPerfilSeleccionado);
                }
                $htmlTabla = $_POST['html_tabla'];
                $json_ddl = Ddl_Parametrizado::getddlOps("tabla = '$htmlTabla' AND campo in('id_habilidad')", null);

                $response = array(
                    "data" => $datosHabilidades,
                    "idPerfilSeleccionado" => $idPerfilSeleccionado,
                    "ddl_ops" => $json_ddl,
                    "accion" => $accion
                );
                break;

                //////////////////////////////////////////////////////////////////////////////////////////////////////
                //SECCION ESTUDIOS
            case 'cargar_tblEstudios':
                header('Content-type: application/json; charset=utf-8');
                $idPerfilSeleccionado = $_POST['datos'];

                if ($idPerfilSeleccionado != null || $idPerfilSeleccionado != '') {
                    $datosEstudios = PerfilAdm::getEstTrabajador($idPerfilSeleccionado);
                }
                $htmlTabla = $_POST['html_tabla'];
                $json_ddl = Ddl_Parametrizado::getddlOps("tabla= '$htmlTabla' AND campo in('id_estudio')", null);
                //print_r($json_ddl);
                $response = array(
                    "data" => $datosEstudios,
                    "ddl_ops" => $json_ddl,
                    "ididPerfilSeleccionado" => $idPerfilSeleccionado,
                    "accion" => $accion
                );
                break;

            case 'Insertar_tblEstudios':
                header('Content-type: application/json; charset=utf-8');
                $newEstudio = json_decode($_POST['datos']);
                if ($newEstudio != null) {
                    EstudiosAdm::guardarObj($newEstudio);
                }
                $response = array(
                    "data" => $newEstudio->id,
                    "accion" => $accion
                );
                break;

            case 'Modificar_tblEstudios':
                header('Content-type: application/json; charset=utf-8');
                $editarEstudio = json_decode($_POST['datos']);

                if ($editarEstudio != null || $editarEstudio != '') {
                    EstudiosAdm::modificarObj($editarEstudio);
                }

                $response = array(
                    "data" => $editarEstudio->id,
                    "accion" => $accion
                );
                break;

            case 'Eliminar_tblEstudios':
                header('Content-type: application/json; charset=utf-8');
                $eliminarIdEstudio = $_POST['datos'];

                if ($eliminarIdEstudio != null || $eliminarIdEstudio != '') {
                    EstudiosAdm::eliminarObj($eliminarIdEstudio);
                }

                $response = array(
                    "data" => $eliminarIdEstudio,
                    "accion" => $accion
                );
                break;

            case 'cargarArchivo_tblEmpleados':

                // respuesta json por defecto 
                $response = array(
                    'data' => null,
                    'status' => 0,
                    'message' => 'La carga del archivo ha fallado, intente nuevamente.'
                );
                $archivo = $_FILES['file']['name'];
                if (isset($archivo)) {
                    // obtener extensión del archivo
                    $ext = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
                    $response = upload::subirArchivo();
                }
                $response["accion"] = $accion;

                break;

            case 'cargarArchivo_tblEstudios':

                //TODO: hacer verificacion de seguridad, con la ruta verificar que el archivo si es PDF
                $resultado = upload::subirArchivo();

                $response = array(
                    "data" => $resultado,
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
