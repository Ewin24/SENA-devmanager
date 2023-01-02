<?php

require_once '../logica/clasesGenericas/ConectorBD.php';
require_once '../logica/clases/EmpresaAdm.php';

//$idProySeleccionado = 'f660bbbf-dd1a-4eab-9866-dba8092c94c5';

[$t1] = EmpresaAdm::cargarTablasHijas($_GET["nit"]); //se manda por protocolo

$response = array(
    "trabajadores" => $t1
);

echo json_encode($response);
?>