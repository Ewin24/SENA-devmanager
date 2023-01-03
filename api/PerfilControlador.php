<?php

require_once '../logica/clasesGenericas/ConectorBD.php';
require_once '../logica/clases/PerfilAdm.php';

[$estudios, $habilidades] = PerfilAdm::cargarTablasHijas($_GET["id"]);

$response = array(
    "dEstudios" => $estudios,
    "dHabilidades" => $habilidades
);

echo json_encode($response);
