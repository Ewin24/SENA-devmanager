<?php

require_once '../logica/clasesGenericas/ConectorBD.php';
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
            case 'Insertar_tblPerfiles':
                header('Content-type: application/json; charset=utf-8');
                $newPerfil = json_decode($_POST['datos']);
                if ($newPerfil != null) {
                    $newPerfil->id = ConectorBD::get_UUIDv4();
                    PerfilAdm::guardarObj($newPerfil);
                }

                $response = array(
                    "data" => $newPerfil->id,
                    "accion" => $accion
                );
                break;

            case 'Modificar_tblPerfiles':
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

            case 'Eliminar_tblPerfiles':
                header('Content-type: application/json; charset=utf-8');
                $eliminarPerfil = json_decode($_POST['datos']);

                if ($eliminarPerfil != null || $eliminarPerfil != '') {
                    PerfilAdm::eliminarObj($eliminarPerfil->id);
                }

                $response = array(
                    "data" => $eliminarPerfil,
                    "accion" => $accion
                );
                break;

            case 'cargar_tblPerfiles':
                header('Content-type: application/json; charset=utf-8');
                $idUsuario = $_POST['datos'];
                $USUARIO = Usuario::getListaEnObjetos("id='$idUsuario'", null)[0];
                $tipoUsuario = $USUARIO->getTipo_usuario();

                switch ($tipoUsuario) {
                    case 'A': //Admin (Modo CRUD): muestra todos los perfiles y opciones porque es admin
                        $datosProyectos = Perfil::getListaEnObjetos(null, null);
                        $modoTabla = 'CRUD';
                        break;

                    case 'D': //Director (modo CRUD filtrado): solo su información de perfil activo
                        $filtroUsuario = "id='$idUsuario'";
                        $datosProyectos = Perfil::getListaEnObjetos($filtroUsuario, null);
                        $modoTabla = 'RU';
                        break;

                    default: //trabajador (modo: Solo lectura): perfiles existentes
                        $datosProyectos = Usuario::getProyectosUsuario($idUsuario);
                        $modoTabla = 'R';
                        break;
                }

                $response = array(
                    "data" => $datosProyectos,
                    "tipoUsuario" => $tipoUsuario
                );
                // $response = $datosProyectos;
                break;

                //////////////////////////////////////////////////////////////////////////////////////////////////////
                //SECCION HABILIDADES
            case 'cargar_tblHabilidades':
                header('Content-type: application/json; charset=utf-8');
                $idPerfilSeleccionado = $_POST['datos'];

                if ($idPerfilSeleccionado != null || $idPerfilSeleccionado != '') {
                    $datosHabilidades = PerfilAdm::getHabTrabajador($idPerfilSeleccionado);
                }

                $response = array(
                    "data" => $datosHabilidades,
                    "idPerfilSeleccionado" => $idPerfilSeleccionado,
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

                $response = array(
                    "data" => $datosEstudios,
                    "ididPerfilSeleccionado" => $idPerfilSeleccionado,
                    "accion" => $accion
                );
                break;

            case 'Insertar_tblEstudios':

                echo "hola";
                print_r($_POST);
                print_r($_FILES);
                $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc' , 'ppt'); // valid extensions
                $path = 'pdfs/'; // upload directory
                if($_FILES['_FILES'])
                {
                    $pdf = $_FILES['pdf']['name'];
                    $tmp = $_FILES['pdf']['tmp_name'];

                    // get uploaded file's extension
                    $ext = strtolower(pathinfo($pdf, PATHINFO_EXTENSION));
                    // can upload same image using rand function
                    $final_image = rand(1000,1000000).$pdf;
                    // check's valid format
                    if(in_array($ext, $valid_extensions)) 
                    { 
                        $path = $path.strtolower($final_image); 
                        if(move_uploaded_file($tmp,$path)) 
                        {
                            $response = "Exito en la carga de $location"; 
                            //insert form data in the database
                            // $insert = $db->query("INSERT uploading (name,email,file_name) VALUES ('".$name."','".$email."','".$path."')");
                            //echo $insert?'ok':'err';
                        }
                        else 
                        {
                            $response = "Fallo la carga de $location"; 
                        }
                    }
                    else 
                    {
                        $response = "Extensión de archivo no permitida"; 
                    }
                    
                }


                // echo "hola";
                // print_r($_POST);
                // print_r($_FILES);
                
                // if($_FILES['pdf'])
                // {
                //     $img = $_FILES['pdf']['name'];
                //     $tmp = $_FILES['pdf']['tmp_name'];
                //     echo $img, $tmp;
                // }

                // // Crea la carpeta para almacenar los archivos PDF si no existe
                // if (!is_dir('pdfs')) {
                //     mkdir('pdfs');
                // }

                // /* Get the name of the uploaded file */
                // $filename = $_FILES['pdf']['name'];

                // /* Choose where to save the uploaded file */
                // $location = "pdfs/".$filename;

                // /* Save the uploaded file to the local filesystem */
                // if ( move_uploaded_file($_FILES['pdf']['tmp_name'], $location) ) { 
                //     $response = "Exito en la carga de $location"; 
                // } 
                // else { 
                //     $response = "Fallo la carga de $location"; 
                // }

                $response = array(
                    "data" => $response,
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
    }
    catch (customException $e) {
        $response = array(
            "data" => array(),
            "accion" => "Error generado en $accion",
            "error" => $e->errorMessage()
        );
    } 
    catch (Exception $e) {
        $response = array(
            "data" => array(),
            "accion" => "Error generado en $accion",
            "error" => $e->getMessage()
        );
    }
    // Enviando respuesta hacia el frontEnd
    echo json_encode($response);
}
