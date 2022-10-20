<?php
//video 11.8
session_start();
if (!isset($_SESSION['usuario'])) {
    header('location: index.php?mensaje=Acceso no autorizado');
}

$perfil = new Perfil(null, null);

$idPerfil = $_REQUEST['idPerfil'];

switch ($_REQUEST['accion']) {
    case 'Adicionar':
        $perfil->setNombre($_REQUEST['nombre']);
        $perfil->setDescripcion($_REQUEST['descripcion']);
        $perfil->guardar();
        break;

    case 'Modificar':
        $perfil->setIdPerfil($_REQUEST['idPerfil']);
        $perfil->setNombre($_REQUEST['nombre']);
        $perfil->setDescripcion($_REQUEST['descripcion']);
        $perfil->modificar();
        break;

    case 'Eliminar':
        $perfil->setIdPerfil($_REQUEST['idPerfil']);
        $perfil->eliminar();
        break;

    default:
        break;
}

header('location: principal.php?CONTENIDO=presentacion/vistas/perfil.php'); //regresa a la pagina para continuar las acciones
