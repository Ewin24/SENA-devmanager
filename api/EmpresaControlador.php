<?php

require_once '../logica/clasesGenericas/ConectorBD.php';
require_once '../logica/clases/EmpresaAdm.php';

[$t1] = EmpresaAdm::cargarTablasHijas($_GET["nit"]); //se manda por protocolo
//print_r($t1);
$response = array(
    "trabajadores" => $t1
);

echo json_encode($response);
?>