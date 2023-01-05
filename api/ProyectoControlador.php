<?php

require_once '../logica/clasesGenericas/ConectorBD.php';
require_once '../logica/clases/ProyectoAdm.php';
require_once '../logica/clases/Proyecto.php';


if(!empty($_POST['action'])) {

    $accion = $_POST['action'];

    switch ($accion) {
        case 'Insertar':
            header('Content-type: application/json; charset=utf-8');
            $newProyecto = json_decode($_POST['datos']);
            $newProyecto->id = ConectorBD::get_UUIDv4();
            Proyecto::guardarObj($newProyecto);
            break;
    
        case 'Modificar':
            $habilidad->setIdHabilidad($idHabiliad);
            $habilidad->setNombre($_REQUEST['nombre']);
            $habilidad->setDescripcion($_REQUEST['descripcion']);
            $habilidad->modificar();
            break;
    
        case 'Eliminar':
            $habilidad->setIdHabilidad($idHabiliad);
            $habilidad->eliminar();
            break;
    
        default:
            # code...
            break;
            
    }
    // Sending response to script file 
    echo json_encode("Success");; // "Acción $accion Exitosa";
}

if($_POST['action'] == 'cargarTablasHijas') {
    [$t1, $t2, $t3, $t4] = ProyectoAdm::cargarTablasHijas($_POST['idProy']);
    $response = array(
        "dHabReq" => $t1,
        "dHabDisp" => $t2,
        "dTrabReq"  => $t3,
        "dTrabDisp" =>  $t4
    );

    echo json_encode($response);
}


// if(!empty($_POST['action']) && $_POST['action'] == 'listProy') {
// 	$emp->empList();
// }
// if(!empty($_POST['action']) && $_POST['action'] == 'getProy') {
// 	$emp->getEmp();
// }
// if(!empty($_POST['action']) && $_POST['action'] == 'updateProy') {
// 	$emp->updateEmp();
// }
// if(!empty($_POST['action']) && $_POST['action'] == 'deleteProy') {
//     echo "<hola controlador";
// }

// [$t1, $t2, $t3, $t4] = ProyectoAdm::cargarTablasHijas($_GET["id"]);

// $response = array(
//     "dHabReq" => $t1,
//     "dHabDisp" => $t2,
//     "dTrabReq"  => $t3,
//     "dTrabDisp" =>  $t4
// );

// echo json_encode($response);

?>