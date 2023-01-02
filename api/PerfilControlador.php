<?php

require_once '../logica/clasesGenericas/ConectorBD.php';
require_once '../logica/clases/Perfil.php';

[$estudios, $habilidades] = Perfil::cargarTablasHijas($_GET["id"]);

print_r($estudios);

$response = array(
    "dEstudios" => $estudios,
    "dHabilidades" => $habilidades
);

echo json_encode($response);