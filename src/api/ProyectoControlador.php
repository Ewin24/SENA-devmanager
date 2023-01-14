<?php

require_once '../logica/clasesGenericas/Excepciones.php';
require_once '../logica/clasesGenericas/ConectorBD.php';
require_once '../logica/clasesGenericas/ddl_parametrizado.php';
require_once '../logica/clases/ProyectoAdm.php';
require_once '../logica/clases/Proyecto.php';
require_once '../logica/clases/Usuario.php';


if (!empty($_POST['action'])) {

    // try {

    $accion = $_POST['action'];
    $response = '';
    switch ($accion) {

            ///////////////////////////////////////////////////////////////////////////////////////////
            //SECCION DE PROYECTOS
        case 'Insertar_tblProyectos':
            header('Content-type: application/json; charset=utf-8');
            $newProyecto = json_decode($_POST['datos']);
            // echo $newProyecto->nombre;
            // echo $newProyecto->estado;
            if ($newProyecto != null) {
                $newProyecto->id = ConectorBD::get_UUIDv4();
                ProyectoAdm::guardarObj($newProyecto);
            }

            $response = array(
                "data" => $newProyecto->id,
                "accion" => $accion
            );
            break;

        case 'Modificar_tblProyectos':
            header('Content-type: application/json; charset=utf-8');
            $editarProyecto = json_decode($_POST['datos']);

            if ($editarProyecto != null || $editarProyecto != '') {
                ProyectoAdm::modificarObj($editarProyecto);
            }

            $response = array(
                "data" => $editarProyecto,
                "accion" => $accion
            );
            break;

        case 'Eliminar_tblProyectos':
            header('Content-type: application/json; charset=utf-8');
            $eliminarIdProyecto = $_POST['datos'];

            if ($eliminarIdProyecto != null || $eliminarIdProyecto != '') {
                ProyectoAdm::eliminarObj($eliminarIdProyecto);
            }

            $response = array(
                "data" => $eliminarIdProyecto,
                "accion" => $accion
            );
            break;

        case 'cargar_tblProyectos':
            header('Content-type: application/json; charset=utf-8');
            $idUsuario = $_POST['datos'];
            $USUARIO = Usuario::getListaEnObjetos("id='$idUsuario'", null)[0];
            $tipoUsuario = $USUARIO->getTipo_usuario();

            switch ($tipoUsuario) {
                case 'A': //Admin (Modo CRUD): muestra todos los perfiles y opciones porque es admin
                    $datosProyectos = Proyecto::getListaEnObjetos(null, null);
                    $modoTabla = "'CRUD'";
                    break;

                case 'D': //Director (modo CRUD filtrado): solo su información de perfil activo
                    $filtroUsuario = "id_usuario='$idUsuario'";
                    $datosProyectos = Proyecto::getListaEnObjetos($filtroUsuario, null);
                    // R solo lectura
                    $modoTabla = "'CRUD'";
                    break;

                default: //trabajador (modo: Solo lectura): perfiles existentes
                    $datosProyectos = Usuario::getProyectosUsuario($idUsuario);
                    $modoTabla = "'R'";
                    break;
            }

            $json_ddl = "{
                    'estado': {
                        'E': 'Ejec',
                        'P': 'Pend'
                    },
                    'correo_director': {
                        '8fa903bc-0789-43b2-901b-70d6c60334ba': 'fgarcia@gmail.com',
                        '499a9d4a-fbf1-4ea7-850b-01bf301a98af': 'wtrigos@gmail.com'
                    }
                }";
            $htmlTabla = $_POST['html_tabla']; //'tblProyectos';
            $json_ddl = Ddl_Parametrizado::getddlOps("tabla='$htmlTabla' AND campo in ('estado', 'id_director')", null);
            //echo($json_ddl);
            $response = array(
                "data" => $datosProyectos,
                "ddl_ops" => $json_ddl,
                "tipoUsuario" => $tipoUsuario
            );
            // $response = $datosProyectos;
            break;

            ///////////////////////////////////////////////////////////////////////////////////////////
            //SECCION DE HABILIDADES REQUERIDAS
        case 'Insertar_tblHab_Requeridas':
            header('Content-type: application/json; charset=utf-8');
            $newHabRequerida = json_decode($_POST['datos']);
            if ($newHabRequerida != null) {
                $newHabRequerida->id = ConectorBD::get_UUIDv4();
                ProyectoAdm::guardarObj($newHabRequerida);
            }

            $response = array(
                "data" => $newHabRequerida->id,
                "accion" => $accion
            );
            break;

        case 'Eliminar_tblHab_Requeridas':
            header('Content-type: application/json; charset=utf-8');
            $eliminarHabRequerida = $_POST['datos'];

            if ($eliminarHabRequerida != null || $eliminarHabRequerida != '') {
                ProyectoAdm::eliminarObj($eliminarHabRequerida);
            }

            $response = array(
                "data" => $eliminarHabRequerida,
                "accion" => $accion
            );
            break;

        case 'cargar_tblHab_Requeridas':
            header('Content-type: application/json; charset=utf-8');
            $idProySeleccionado = $_POST['datos'];

            if ($idProySeleccionado != null || $idProySeleccionado != '') {
                //// Definiendo la lógica de negocio dentro de la clase
                $datHabAsignados = ProyectoAdm::getHabilidadesRequeridas($idProySeleccionado);
            }

            $htmlTabla = $_POST['html_tabla']; //'tblHab_Requeridas';
            $json_ddl = Ddl_Parametrizado::getddlOps("tabla='$htmlTabla' AND campo in ('id_proyecto', 'id_habilidad')", null);

            $response = array(
                "data" => $datHabAsignados,
                "ddl_ops" => $json_ddl,
                "idProySeleccionado" => $idProySeleccionado,
                "accion" => $accion
            );

            break;

            ///////////////////////////////////////////////////////////////////////////////////////////
            //SECCION DE HABILIDADES DISPONIBLES
        case 'Insertar_tblHab_Disponibles':
            header('Content-type: application/json; charset=utf-8');
            $newHabDisponible = json_decode($_POST['datos']);
            if ($newHabDisponible != null) {
                $newHabDisponible->id = ConectorBD::get_UUIDv4();
                ProyectoAdm::guardarObjDisponible($newHabDisponible);
            }

            $response = array(
                "data" => $newHabDisponible->id,
                "accion" => $accion
            );
            break;

        case 'Modificar_tblHab_Disponibles':
            header('Content-type: application/json; charset=utf-8');
            $editarHabDisponible = json_decode($_POST['datos']);

            if ($editarHabDisponible != null || $editarHabDisponible != '') {
                ProyectoAdm::modificarObjDisponible($editarHabDisponible);
            }

            $response = array(
                "data" => $editarHabDisponible,
                "accion" => $accion
            );
            break;

        case 'Eliminar_tblHab_Disponibles':
            header('Content-type: application/json; charset=utf-8');
            $eliminarHabDisponible = $_POST['datos'];

            if ($eliminarHabDisponible != null || $eliminarHabDisponible != '') {
                ProyectoAdm::eliminarObjDisponible($eliminarHabDisponible);
            }

            $response = array(
                "data" => $eliminarHabDisponible,
                "accion" => $accion
            );
            break;
        case 'cargar_tblHab_Disponibles':
            header('Content-type: application/json; charset=utf-8');
            $idProySeleccionado = $_POST['datos'];

            if ($idProySeleccionado != null || $idProySeleccionado != '') {
                //// Definiendo la lógica de negocio dentro de la clase
                $datHabDisponibles = ProyectoAdm::getHabilidadesDisponibles($idProySeleccionado);
            }
            $response = array(
                "data" => $datHabDisponibles,
                "idProySeleccionado" => $idProySeleccionado,
                "accion" => $accion
            );
            break;

            ///////////////////////////////////////////////////////////////////////////////////////////
            //SECCION DE CONTRATADOS
        case 'Insertar_tblContratados':
            header('Content-type: application/json; charset=utf-8');
            $trabajadores = $_POST['datos'];
            $idProySeleccionado = $_POST['id_proyecto'];

            if ($idProySeleccionado != null || $idProySeleccionado != '') {
                //// Definiendo la lógica de negocio dentro de la clase
                ProyectoAdm::asignarTrabajadorProyecto($trabajadores, $idProySeleccionado);
            }

            $response = array(
                "data" => $trabajadores,
                "idProySeleccionado" => $idProySeleccionado,
                "accion" => $accion
            );
            break;

        case 'cargar_tblContratados':
            header('Content-type: application/json; charset=utf-8');
            $idProySeleccionado = $_POST['datos'];

            if ($idProySeleccionado != null || $idProySeleccionado != '') {
                //// Definiendo la lógica de negocio dentro de la clase
                $datTrabAsignados = ProyectoAdm::getTrabajadoresAsignados($idProySeleccionado);
            }

            $htmlTabla = $_POST['html_tabla']; //'tblHab_Disponibles';
            $json_ddl = Ddl_Parametrizado::getddlOps("tabla='$htmlTabla' AND campo in ('id_usuario', 'estado')", null);

            $response = array(
                "data" => $datTrabAsignados,
                "ddl_ops" => $json_ddl,
                "idProySeleccionado" => $idProySeleccionado,
                "accion" => $accion
            );
            break;

            ///////////////////////////////////////////////////////////////////////////////////////////
            //SECCION DE CANDIDATOS
        case 'cargar_tblCandidatos':
            header('Content-type: application/json; charset=utf-8');
            $idProySeleccionado = $_POST['datos'];

            if ($idProySeleccionado != null || $idProySeleccionado != '') {
                //// Definiendo la lógica de negocio dentro de la clase
                $datTrabDisponibles = ProyectoAdm::getTrabajadoresDisponibles($idProySeleccionado);
            }

            $htmlTabla = $_POST['html_tabla']; //'tblHab_Disponibles';
            $json_ddl = Ddl_Parametrizado::getddlOps("tabla='$htmlTabla' AND campo in ('id_usuario', 'estado')", null);

            $response = array(
                "data" => $datTrabDisponibles,
                "ddl_ops" => $json_ddl,
                "idProySeleccionado" => $idProySeleccionado,
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
    // }
    // catch (customException $e) {
    //     $response = array(
    //         "data" => array(),
    //         "accion" => "Error generado en $accion",
    //         "error" => $e->errorMessage()
    //     );
    // }

    // catch(Exception $e) {
    //     $response = array(
    //         "data" => array(),
    //         "accion" => "Error generado en $accion",
    //         "error" => $e->getMessage()
    //     );
    // }

    // Enviando respuesta hacia el frontEnd
    echo json_encode($response);
}
