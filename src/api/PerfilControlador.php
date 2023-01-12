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
                $eliminarPerfil = json_decode($_POST['datos']);

                if ($eliminarPerfil != null || $eliminarPerfil != '') {
                    PerfilAdm::eliminarObj($eliminarPerfil->id);
                }

                $response = array(
                    "data" => $eliminarPerfil,
                    "accion" => $accion
                );
                break;

            case 'cargar_tblEmpleados':
                header('Content-type: application/json; charset=utf-8');
                $idUsuario = $_POST['datos'];
                $USUARIO = Usuario::getListaEnObjetos("id='$idUsuario'", null)[0];
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
            case 'cargar_tblHabilidades':
                header('Content-type: application/json; charset=utf-8');
                $idPerfilSeleccionado = $_POST['datos'];

                if ($idPerfilSeleccionado != null || $idPerfilSeleccionado != '') {
                    $datosHabilidades = PerfilAdm::getHabTrabajador($idPerfilSeleccionado);
                }
                $htmlTabla = $_POST['html_tabla'];
                $json_ddl = Ddl_Parametrizado::getddlOps("tabla= '$htmlTabla' AND campo in('id_habilidad')", null);

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

            case 'cargarArchivo_tblEstudios':

                // File upload folder 
                $uploadDir='pdfs/'; 

                // Allowed file types 
                $allowTypes = array('pdf','doc','docx','jpg','png','jpeg'); 

                // Default response 
                // $response = array( 
                //     'status' => 0, 
                //     'message' => 'La carga del archivo ha fallado, intente nuevamente.' 
                // ); 
                $datos = '';
                // If form is submitted 
                if(isset($_FILES['pdf'])){
                    // Crea la carpeta para almacenar los archivos PDF si no existe
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir);
                    }
                    // Upload file 
                    $uploadedFile = ''; 
                    if(!empty($_FILES["pdf"]["name"]))
                    { 
                        // File path config
                        $fileName = basename($_FILES["pdf"]["name"]); 
                        $targetFilePath = $uploadDir.$fileName; 
                        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 

                        // Allow certain file formats to upload 
                        if(in_array($fileType, $allowTypes)){ 

                            echo "tes", in_array($fileType, $allowTypes);
                            // Upload file to the server 
                            $Res = move_uploaded_file($_FILES["pdf"]["tmp_name"], $targetFilePath);
                            echo "Res" . $targetFilePath;
                            if($Res){ 
                                $uploadedFile = $fileName; 
                            }else{ 
                                $uploadStatus = 0; 
                                $datos = 'Ha ocurrido un error en la carga del archivo'; 
                            } 
                        }
                        else
                        {
                            $uploadStatus=0; 
                            $datos ='Solo las extensiones'.implode('/', $allowTypes).' son permitidas para cargar.'; 
                        } 
                    } 
                }
                    
                $response = array(
                    "data" => $datos,
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
