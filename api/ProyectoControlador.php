<?php

require_once '../logica/clasesGenericas/ConectorBD.php';
require_once '../logica/clases/ProyectoAdm.php';
require_once '../logica/clases/Proyecto.php';
require_once '../logica/clases/Usuario.php';


if(!empty($_POST['action'])) {

    $accion = $_POST['action'];
    $response = '';
    switch ($accion) {
        case 'Insertar':
            header('Content-type: application/json; charset=utf-8');
            $newProyecto = json_decode($_POST['datos']);
            $newProyecto->id = ConectorBD::get_UUIDv4();

                // echo $newProyecto->nombre;
                // echo $newProyecto->estado;
                ProyectoAdm::guardarObj($newProyecto);

            $response = "Success";
            break;
    
        case 'Modificar':
            header('Content-type: application/json; charset=utf-8');
            $editarProyecto = json_decode($_POST['datos']);

                ProyectoAdm::modificarObj($editarProyecto);

            $response = "Success";
            break;
    
        case 'Eliminar':
            header('Content-type: application/json; charset=utf-8');
            $eliminarProyecto = json_decode($_POST['datos']);

                ProyectoAdm::eliminarObj($eliminarProyecto->id);
                
            break;

        // case 'cargarTablasHijas':
        //     $idProySeleccionado = $_POST['datos'];
        //     [$t1, $t2, $t3, $t4] = ProyectoAdm::cargarTablasHijas($idProySeleccionado);
        //     $response = array(
        //         "data" => array(
        //             "dHabReq" => $t1,
        //             "dHabDisp" => $t2,
        //             "dTrabReq"  => $t3,
        //             "dTrabDisp" =>  $t4
        //         ),
        //         "proySeleccionado" => $_POST['datos']
        //     );
        //     break;

        case 'cargar_tblProyectos':
            header('Content-type: application/json; charset=utf-8');
            $idUsuario = $_POST['datos'];
            $USUARIO = Usuario::getListaEnObjetos("id='$idUsuario'",null)[0];
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

            $response = array(
                "data" => $datosProyectos,
                "tipoUsuario" => $tipoUsuario
            );
            // $response = $datosProyectos;
            break;

        case 'cargar_tblHab_Requeridas':
            header('Content-type: application/json; charset=utf-8');
            $idProySeleccionado = $_POST['datos'];

                if ($idProySeleccionado != null || $idProySeleccionado != ''){
                    //// Definiendo la lógica de negocio dentro de la clase
                    $datHabAsignados = ProyectoAdm::getHabilidadesRequeridas($idProySeleccionado);
                }

            $response = array(
                "data" => $datHabAsignados,
                "idProySeleccionado" => $idProySeleccionado,
                "accion" => $accion
            );

            break;

        case 'cargar_tblHab_Disponibles':
            header('Content-type: application/json; charset=utf-8');
            $idProySeleccionado = $_POST['datos'];

                if ($idProySeleccionado != null || $idProySeleccionado != ''){
                    //// Definiendo la lógica de negocio dentro de la clase
                    $datHabDisponibles = ProyectoAdm::getHabilidadesDisponibles($idProySeleccionado);
                }
                
            $response = array(
                "data" => $datHabDisponibles,
                "idProySeleccionado" => $idProySeleccionado,
                "accion" => $accion
            );
            break;

        case 'cargar_tblContratados':
            header('Content-type: application/json; charset=utf-8');
            $idProySeleccionado = $_POST['datos'];

                if ($idProySeleccionado != null || $idProySeleccionado != ''){
                    //// Definiendo la lógica de negocio dentro de la clase
                    $datTrabAsignados = ProyectoAdm::getTrabajadoresAsignados($idProySeleccionado);
                }
                
            $response = array(
                "data" => $datTrabAsignados,
                "idProySeleccionado" => $idProySeleccionado,
                "accion" => $accion
            );
            break;

        case 'cargar_tblCandidatos':
            header('Content-type: application/json; charset=utf-8');
            $idProySeleccionado = $_POST['datos'];
                
            if ($idProySeleccionado != null || $idProySeleccionado != ''){
                //// Definiendo la lógica de negocio dentro de la clase
                $datTrabDisponibles = ProyectoAdm::getTrabajadoresDisponibles($idProySeleccionado);
            }

            $response = array(
                "data" => $datTrabDisponibles,
                "idProySeleccionado" => $idProySeleccionado,
                "accion" => $accion
            );
            break;


        default:
            
            # code...
            break;
            
    }
    // Enviando respuesta hacia el frontEnd
    echo json_encode($response); 
}

?>