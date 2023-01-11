<?php
//video 11.8
session_start();
if (!isset($_SESSION['usuario'])) {
    header('location: index.php?mensaje=Acceso no autorizado');
}

$habilidad = new Habilidad(null, null);

$idHabiliad = $_REQUEST['idHabilidad'];

switch ($_REQUEST['accion']) {
    case 'Adicionar':
        $habilidad->setNombre($_REQUEST['nombre']);
        $habilidad->setDescripcion($_REQUEST['descripcion']);
        $habilidad->guardar();
        //$habilidad->getIdHabiliad('nombre', $_REQUEST['nombre']);
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

header('location: principal.php?CONTENIDO=presentacion/vistas/habilidad.php'); //regresa a la pagina para continuar las acciones
