<?php

require_once '../logica/clasesGenericas/ConectorBD.php';
require_once '../logica/clases/ProyectoAdm.php';


$idProySeleccionado = 'f660bbbf-dd1a-4eab-9866-dba8092c94c5';

[$t1, $t2, $t3, $t4] = ProyectoAdm::cargarTablasHijas($_GET["id"]);

$response = array(
    "dHabReq" => $t1,
    "dHabDisp" => $t2,
    "dTrabReq"  => $t3,
    "dTrabDisp" =>  $t4
);

echo json_encode($response);

?>