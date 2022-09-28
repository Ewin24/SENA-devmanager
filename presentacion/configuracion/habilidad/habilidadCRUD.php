<?php
//video 11.8
session_start();
if (!isset($_SESSION['usuario'])) {
    header('location: index.php?mensaje=Acceso no autorizado');
}

$habilidad = new Habilidad(null, null);
switch ($_REQUEST['accion']) {
    case 'adicionar':
        $habilidad->setNombre($_REQUEST['nombre']);
        $habilidad->setDescripcion($_REQUEST['descripcion']);
        $habilidad->guardar();
        break;

    default:
        # code...
        break;
}
